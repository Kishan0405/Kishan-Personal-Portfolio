<?php
ob_start();

require_once __DIR__ . '/header.php';

$posts_db_host = 'localhost';
$posts_db_name = 'personal_portfolio_db';
$posts_db_user = 'root';
$posts_db_pass = '';
$posts_db_charset = 'utf8mb4';

$posts_dsn = "mysql:host=$posts_db_host;dbname=$posts_db_name;charset=$posts_db_charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo_posts = new PDO($posts_dsn, $posts_db_user, $posts_db_pass, $options);
} catch (\PDOException $e) {
    die("Posts DB Error: " . $e->getMessage());
}

function time_ago(string $datetime): string
{
    $time = strtotime($datetime);
    if ($time === false) return "Invalid date";
    $now = time();
    $seconds = $now - $time;
    if ($seconds < 60) return "just now";
    elseif ($seconds < 3600) {
        $m = floor($seconds / 60);
        return $m == 1 ? "1 min ago" : "$m mins ago";
    } elseif ($seconds < 86400) {
        $h = floor($seconds / 3600);
        return $h == 1 ? "1 hr ago" : "$h hrs ago";
    } elseif ($seconds < 604800) {
        $d = floor($seconds / 86400);
        return $d == 1 ? "1 day ago" : "$d days ago";
    } else return date("M j, Y", $time);
}

function convertTimezone(string $dt, string $from, string $to): string
{
    try {
        $d = new DateTime($dt, new DateTimeZone($from));
        $d->setTimezone(new DateTimeZone($to));
        return $d->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        return $dt;
    }
}

$userLoggedIn = isLoggedIn();
$isAdmin = ($userLoggedIn && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUserProfilePicture = 'https://api.dicebear.com/7.x/initials/svg?seed=Guest';
$currentUserName = 'Guest';

if ($userLoggedIn && isset($pdo)) {
    $stmt = $pdo->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
    $stmt->execute([$currentUserId]);
    $u = $stmt->fetch();
    if ($u) {
        $currentUserName = $u['username'];
        $currentUserProfilePicture = !empty($u['profile_picture']) ? htmlspecialchars($u['profile_picture']) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($u['username']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        ob_clean();
        header('Content-Type: application/json');
        if (!$userLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }

        $act = $_POST['action'];
        $pid = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

        try {
            if ($act === 'like_post') {
                $chk = $pdo_posts->prepare("SELECT id FROM post_likes WHERE user_id=? AND post_id=?");
                $chk->execute([$currentUserId, $pid]);
                if ($chk->fetch()) {
                    $pdo_posts->prepare("DELETE FROM post_likes WHERE user_id=? AND post_id=?")->execute([$currentUserId, $pid]);
                    $liked = false;
                } else {
                    $pdo_posts->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?,?)")->execute([$currentUserId, $pid]);
                    $liked = true;
                }
                $cnt = $pdo_posts->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id=?");
                $cnt->execute([$pid]);
                echo json_encode(['success' => true, 'liked' => $liked, 'likeCount' => $cnt->fetchColumn()]);
            } elseif ($act === 'add_comment') {
                $txt = trim($_POST['comment_text'] ?? '');
                if (!$txt) throw new Exception("Empty comment");
                $utc = convertTimezone(date('Y-m-d H:i:s'), 'Asia/Kolkata', 'UTC');

                $stmt = $pdo_posts->prepare("INSERT INTO post_comments (user_id, post_id, comment_text, created_at) VALUES (?,?,?,?)");
                $stmt->execute([$currentUserId, $pid, $txt, $utc]);

                $html = '
                <div class="flex items-start gap-3 animate-fade-in">
                    <img src="' . $currentUserProfilePicture . '" class="w-8 h-8 rounded-full object-cover border border-[var(--border)]">
                    <div class="flex-1 bg-[var(--bg-tertiary)] rounded-2xl rounded-tl-none p-3">
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-sm text-[var(--text-primary)]">' . htmlspecialchars($currentUserName) . '</span>
                            <span class="text-xs text-[var(--text-tertiary)]">Just now</span>
                        </div>
                        <p class="text-sm text-[var(--text-secondary)] mt-1">' . htmlspecialchars($txt) . '</p>
                    </div>
                </div>';
                echo json_encode(['success' => true, 'commentHtml' => $html]);
            } elseif ($act === 'vote_poll') {
                $optId = $_POST['poll_option_id'];
                $pollId = $_POST['poll_id'];
                $pdo_posts->prepare("INSERT INTO poll_votes (user_id, poll_id, poll_option_id) VALUES (?,?,?) ON DUPLICATE KEY UPDATE poll_option_id=VALUES(poll_option_id)")->execute([$currentUserId, $pollId, $optId]);
                echo json_encode(['success' => true]);
            } elseif ($act === 'delete_post') {
                $stmt = $pdo_posts->prepare("SELECT user_id, image_path FROM posts WHERE id=?");
                $stmt->execute([$pid]);
                $p = $stmt->fetch();
                if (!$p || (!$isAdmin && $p['user_id'] != $currentUserId)) throw new Exception("Unauthorized");

                $pdo_posts->beginTransaction();
                $poll = $pdo_posts->prepare("SELECT id FROM polls WHERE post_id=?");
                $poll->execute([$pid]);
                if ($pl = $poll->fetch()) {
                    $pdo_posts->prepare("DELETE FROM poll_votes WHERE poll_id=?")->execute([$pl['id']]);
                    $pdo_posts->prepare("DELETE FROM poll_options WHERE poll_id=?")->execute([$pl['id']]);
                    $pdo_posts->prepare("DELETE FROM polls WHERE id=?")->execute([$pl['id']]);
                }
                $pdo_posts->prepare("DELETE FROM post_likes WHERE post_id=?")->execute([$pid]);
                $pdo_posts->prepare("DELETE FROM post_comments WHERE post_id=?")->execute([$pid]);
                $pdo_posts->prepare("DELETE FROM posts WHERE id=?")->execute([$pid]);
                $pdo_posts->commit();

                if ($p['image_path'] && file_exists($p['image_path'])) unlink($p['image_path']);
                echo json_encode(['success' => true]);
            }
        } catch (Exception $e) {
            if ($pdo_posts->inTransaction()) $pdo_posts->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit();
    }

    if ($isAdmin && isset($_POST['create_post'])) {
        $content = trim($_POST['content'] ?? '');
        $pollQ = trim($_POST['poll_question'] ?? '');
        $pollOpts = array_values(array_filter($_POST['poll_options'] ?? []));
        $imgPath = null;

        if (!empty($_FILES['post_image']['name'])) {
            $dir = 'uploadsposts/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
            $target = $dir . uniqid('post_') . '.' . $ext;
            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target)) $imgPath = $target;
        }

        if ($content || $imgPath || $pollQ) {
            try {
                $utc = convertTimezone(date('Y-m-d H:i:s'), 'Asia/Kolkata', 'UTC');
                $pdo_posts->beginTransaction();
                $stmt = $pdo_posts->prepare("INSERT INTO posts (user_id, content, image_path, created_at) VALUES (?,?,?,?)");
                $stmt->execute([$currentUserId, $content, $imgPath, $utc]);
                $newPid = $pdo_posts->lastInsertId();

                if ($pollQ && count($pollOpts) >= 2) {
                    $pdo_posts->prepare("INSERT INTO polls (post_id, question) VALUES (?,?)")->execute([$newPid, $pollQ]);
                    $plid = $pdo_posts->lastInsertId();
                    $ins = $pdo_posts->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?,?)");
                    foreach ($pollOpts as $o) $ins->execute([$plid, $o]);
                }
                $pdo_posts->commit();
                header("Location: posts.php");
                exit;
            } catch (Exception $e) {
                $pdo_posts->rollBack();
                $errors[] = "DB Error: " . $e->getMessage();
            }
        }
    }
}

$posts = [];
$likesMap = [];
$commentsMap = [];
$pollsMap = [];
$usersMap = [];

try {
    $stmt = $pdo_posts->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();

    if ($posts) {
        $pids = array_column($posts, 'id');
        $uids = array_column($posts, 'user_id');
        $in = implode(',', array_fill(0, count($pids), '?'));

        $stmt = $pdo_posts->prepare("SELECT post_id, user_id FROM post_likes WHERE post_id IN ($in)");
        $stmt->execute($pids);
        while ($r = $stmt->fetch()) {
            $likesMap[$r['post_id']][] = $r['user_id'];
            $uids[] = $r['user_id'];
        }

        $stmt = $pdo_posts->prepare("SELECT * FROM post_comments WHERE post_id IN ($in) ORDER BY created_at ASC");
        $stmt->execute($pids);
        while ($r = $stmt->fetch()) {
            $commentsMap[$r['post_id']][] = $r;
            $uids[] = $r['user_id'];
        }

        $stmt = $pdo_posts->prepare("SELECT p.post_id, p.id as poll_id, p.question, po.id as opt_id, po.option_text, (SELECT COUNT(*) FROM poll_votes WHERE poll_option_id=po.id) as votes FROM polls p JOIN poll_options po ON p.id=po.poll_id WHERE p.post_id IN ($in)");
        $stmt->execute($pids);
        while ($r = $stmt->fetch()) {
            $pid = $r['post_id'];
            if (!isset($pollsMap[$pid])) $pollsMap[$pid] = ['id' => $r['poll_id'], 'q' => $r['question'], 'total' => 0, 'options' => [], 'myVote' => null];
            $pollsMap[$pid]['options'][] = ['id' => $r['opt_id'], 'text' => $r['option_text'], 'votes' => $r['votes']];
            $pollsMap[$pid]['total'] += $r['votes'];
        }
        if ($userLoggedIn) {
            $stmt = $pdo_posts->prepare("SELECT poll_id, poll_option_id FROM poll_votes WHERE user_id=?");
            $stmt->execute([$currentUserId]);
            while ($r = $stmt->fetch()) {
                foreach ($pollsMap as &$pm) if ($pm['id'] == $r['poll_id']) $pm['myVote'] = $r['poll_option_id'];
            }
        }

        $uids = array_unique(array_filter($uids));
        if ($uids) {
            $inUser = implode(',', array_fill(0, count($uids), '?'));
            $stmt = $pdo->prepare("SELECT id, username, profile_picture FROM users WHERE id IN ($inUser)");
            $stmt->execute(array_values($uids));
            while ($u = $stmt->fetch()) $usersMap[$u['id']] = $u;
        }
    }
} catch (Exception $e) {
    $errors[] = "Data Error";
}

$pageTitle = "Community Posts";
$pageDescription = "Connect with others, share updates, and vote.";
?>

<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <style>
        :root {
            --bg-primary: #F8F9FA;
            --bg-secondary: #FFFFFF;
            --bg-tertiary: #F1F3F4;
            --surface: rgba(0, 0, 0, 0.05);
            --surface-hover: rgba(0, 0, 0, 0.08);
            --border: rgba(0, 0, 0, 0.12);
            --border-hover: rgba(0, 0, 0, 0.20);
            --text-primary: #202124;
            --text-secondary: #5F6368;
            --text-tertiary: #9AA0A6;
            --accent: #4255FF;
            --accent-hover: #3546D9;
            --accent-secondary: #8A2BE2;
            --success: #00FF88;
            --warning: #FFB800;
            --error: #FF4757;
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.9);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-neon: 0 0 20px rgba(66, 85, 255, 0.3);
            --animated-bg-gradient-1: #E6E6FA;
            --animated-bg-gradient-2: #F0F8FF;
        }

        html.dark {
            --bg-primary: #0D1117;
            --bg-secondary: #161B22;
            --bg-tertiary: #21262D;
            --surface: rgba(255, 255, 255, 0.05);
            --surface-hover: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.12);
            --border-hover: rgba(255, 255, 255, 0.20);
            --text-primary: #E6EDF3;
            --text-secondary: #8B949E;
            --text-tertiary: #A5B4C4;
            --accent: #58A6FF;
            --accent-hover: #4796F2;
            --accent-secondary: #A052E8;
            --success: #00FF88;
            --warning: #FFB800;
            --error: #FF4757;
            --glass-bg: rgba(22, 27, 34, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --shadow-neon: 0 0 20px rgba(88, 166, 255, 0.3);
            --animated-bg-gradient-1: #10142C;
            --animated-bg-gradient-2: #1C0E2A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            background: var(--bg-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @keyframes bg-gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .font-space {
            font-family: 'Space Grotesk', monospace;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(14px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        .glass-hover:hover {
            background: var(--surface-hover);
            border-color: var(--border-hover);
            transform: translateY(-2px);
            transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
            box-shadow: 0 4px 15px var(--shadow-neon);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--shadow-neon);
        }

        .poll-progress {
            height: 100%;
            border-radius: 4px;
            background: var(--accent);
            opacity: 0.15;
            position: absolute;
            top: 0;
            left: 0;
            transition: width 0.5s;
        }

        .poll-option.voted .poll-progress {
            opacity: 0.3;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            animation: slideUp 0.3s forwards;
        }

        @keyframes slideUp {
            from {
                transform: translate(-50%, 20px);
                opacity: 0;
            }

            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        @media (min-width: 1024px) {
            .main-content-wrapper {
                margin-left: 15rem;
            }
        }

        @media (max-width: 1023px) {
            .main-content-wrapper {
                margin-left: 0;
                padding-top: 0;
            }
        }
    </style>
</head>

<body>
    <?php include_once 'header.php'; ?>

    <main class="main-content-wrapper flex-1 px-4 py-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="<?= $isAdmin ? 'lg:col-span-8' : 'lg:col-span-8 lg:col-start-3' ?>">
                <div class="text-center mb-10" data-aos="fade-up">
                    <h1 class="text-4xl font-bold gradient-text mb-2 font-space">Community Feed</h1>
                    <p class="text-[var(--text-secondary)]">Join the conversation.</p>
                </div>

                <?php if (empty($posts)): ?>
                    <div class="glass rounded-3xl p-12 text-center" data-aos="fade-up">
                        <i class="fas fa-comments text-4xl text-[var(--text-tertiary)] mb-4"></i>
                        <p class="text-[var(--text-secondary)]">No posts yet. Be the first!</p>
                    </div>
                <?php endif; ?>

                <div class="space-y-8">
                    <?php foreach ($posts as $post):
                        $pid = $post['id'];
                        $likes = $likesMap[$pid] ?? [];
                        $comments = $commentsMap[$pid] ?? [];
                        $poll = $pollsMap[$pid] ?? null;
                        $author = $usersMap[$post['user_id']] ?? ['username' => 'User', 'profile_picture' => ''];
                        $pic = $author['profile_picture'] ?: 'https://api.dicebear.com/7.x/initials/svg?seed=' . $author['username'];
                    ?>
                        <article class="glass rounded-3xl p-6 md:p-8 glass-hover" id="post-<?= $pid ?>" data-aos="fade-up">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <img src="<?= $pic ?>" class="w-12 h-12 rounded-full object-cover border-2 border-[var(--accent)]">
                                    <div>
                                        <h3 class="font-bold text-lg"><?= htmlspecialchars($author['username']) ?></h3>
                                        <span class="text-xs text-[var(--text-tertiary)]"><?= time_ago(convertTimezone($post['created_at'], 'UTC', 'Asia/Kolkata')) ?></span>
                                    </div>
                                </div>
                                <?php if ($isAdmin || $post['user_id'] == $currentUserId): ?>
                                    <button onclick="deletePost(<?= $pid ?>)" class="text-[var(--text-tertiary)] hover:text-[var(--error)] p-2 rounded-full hover:bg-[var(--surface)] transition"><i class="fas fa-trash-alt"></i></button>
                                <?php endif; ?>
                            </div>

                            <div class="mb-5">
                                <p class="text-[var(--text-secondary)] whitespace-pre-wrap text-base mb-4"><?= htmlspecialchars($post['content']) ?></p>
                                <?php if ($post['image_path']): ?>
                                    <img src="<?= htmlspecialchars($post['image_path']) ?>" class="rounded-2xl w-full max-h-[500px] object-cover border border-[var(--border)]">
                                <?php endif; ?>
                            </div>

                            <?php if ($poll): ?>
                                <div class="bg-[var(--bg-tertiary)] rounded-2xl p-5 mb-5 border border-[var(--border)]">
                                    <h4 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-poll text-[var(--accent)]"></i> <?= htmlspecialchars($poll['q']) ?></h4>
                                    <div class="space-y-3" id="poll-<?= $pid ?>">
                                        <?php foreach ($poll['options'] as $opt):
                                            $pct = $poll['total'] > 0 ? round(($opt['votes'] / $poll['total']) * 100) : 0;
                                            $isVoted = $poll['myVote'] == $opt['id'];
                                        ?>
                                            <div class="poll-option <?= $isVoted ? 'voted' : '' ?> relative bg-[var(--surface)] rounded-xl border border-[var(--border)] p-3 cursor-pointer hover:border-[var(--accent)] transition" onclick="votePoll(<?= $pid ?>, <?= $poll['id'] ?>, <?= $opt['id'] ?>)">
                                                <div class="poll-progress" style="width: <?= $pct ?>%"></div>
                                                <div class="relative z-10 flex justify-between items-center">
                                                    <span class="font-medium text-sm <?= $isVoted ? 'text-[var(--accent)]' : 'text-[var(--text-secondary)]' ?>">
                                                        <?= htmlspecialchars($opt['text']) ?> <?php if ($isVoted): ?><i class="fas fa-check-circle ml-1"></i><?php endif; ?>
                                                    </span>
                                                    <span class="text-xs font-bold"><?= $pct ?>%</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex gap-6 border-t border-[var(--border)] pt-4">
                                <button onclick="likePost(<?= $pid ?>, this)" class="flex items-center gap-2 group">
                                    <i class="<?= in_array($currentUserId, $likes) ? 'fas text-[var(--error)]' : 'far text-[var(--text-tertiary)] group-hover:text-[var(--error)]' ?> fa-heart text-xl transition"></i>
                                    <span class="font-medium text-sm text-[var(--text-secondary)]"><?= count($likes) ?></span>
                                </button>
                                <button onclick="toggleComments(<?= $pid ?>)" class="flex items-center gap-2 group">
                                    <i class="far fa-comment text-[var(--text-tertiary)] group-hover:text-[var(--accent)] text-xl transition"></i>
                                    <span class="font-medium text-sm text-[var(--text-secondary)]"><?= count($comments) ?></span>
                                </button>
                            </div>

                            <div id="comments-<?= $pid ?>" class="hidden mt-6 pt-4 border-t border-[var(--border)] animate-fade-in">
                                <?php if ($userLoggedIn): ?>
                                    <form onsubmit="postComment(event, <?= $pid ?>)" class="flex gap-3 mb-6">
                                        <img src="<?= $currentUserProfilePicture ?>" class="w-8 h-8 rounded-full object-cover mt-1">
                                        <div class="flex-1 relative">
                                            <input name="comment_text" type="text" placeholder="Write a comment..." class="w-full bg-[var(--bg-tertiary)] rounded-full py-2 px-4 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent)] border border-[var(--border)] transition">
                                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--accent)] hover:scale-110 transition"><i class="fa-regular fa-pen-to-square"></i></button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                                <div class="space-y-4 comment-list">
                                    <?php foreach ($comments as $c):
                                        $cAuth = $usersMap[$c['user_id']] ?? ['username' => 'User', 'profile_picture' => ''];
                                        $cPic = $cAuth['profile_picture'] ?: 'https://api.dicebear.com/7.x/initials/svg?seed=' . $cAuth['username'];
                                    ?>
                                        <div class="flex gap-3">
                                            <img src="<?= $cPic ?>" class="w-8 h-8 rounded-full object-cover border border-[var(--border)]">
                                            <div class="flex-1 bg-[var(--bg-tertiary)] rounded-2xl rounded-tl-none p-3">
                                                <div class="flex justify-between items-baseline">
                                                    <span class="font-bold text-sm text-[var(--text-primary)]"><?= htmlspecialchars($cAuth['username']) ?></span>
                                                    <span class="text-xs text-[var(--text-tertiary)]"><?= time_ago(convertTimezone($c['created_at'], 'UTC', 'Asia/Kolkata')) ?></span>
                                                </div>
                                                <p class="text-sm text-[var(--text-secondary)] mt-1"><?= htmlspecialchars($c['comment_text']) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($isAdmin): ?>
                <div class="lg:col-span-4">
                    <div class="sticky top-24 glass rounded-3xl p-8 border border-[var(--border)]">
                        <h2 class="text-2xl font-bold mb-6 font-space flex items-center gap-2">
                            <span class="bg-[var(--accent)] w-2 h-8 rounded-full inline-block"></span> Create Post
                        </h2>
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-tertiary)] uppercase tracking-wider mb-2">Content</label>
                                <textarea name="content" rows="4" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-xl p-4 focus:ring-2 focus:ring-[var(--accent)] focus:border-transparent outline-none transition resize-none" placeholder="What's new?"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[var(--text-tertiary)] uppercase tracking-wider mb-2">Image</label>
                                <div class="relative">
                                    <input type="file" name="post_image" id="file-upload" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                    <label for="file-upload" class="w-full flex items-center gap-3 p-3 rounded-xl border-2 border-dashed border-[var(--border)] cursor-pointer hover:border-[var(--accent)] hover:bg-[var(--surface)] transition group">
                                        <div class="w-10 h-10 rounded-full bg-[var(--bg-tertiary)] flex items-center justify-center group-hover:bg-[var(--accent)] group-hover:text-white transition"><i class="fas fa-image"></i></div>
                                        <span class="text-sm text-[var(--text-secondary)] truncate" id="file-name">Choose an image...</span>
                                    </label>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-[var(--border)]">
                                <label class="block text-xs font-bold text-[var(--text-tertiary)] uppercase tracking-wider mb-2">Poll (Optional)</label>
                                <input type="text" name="poll_question" placeholder="Ask a question..." class="w-full mb-3 bg-[var(--surface)] border border-[var(--border)] rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-[var(--accent)] outline-none">
                                <div class="space-y-2">
                                    <input type="text" name="poll_options[]" placeholder="Option 1" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-lg px-3 py-2 text-sm focus:border-[var(--accent)] outline-none">
                                    <input type="text" name="poll_options[]" placeholder="Option 2" class="w-full bg-[var(--surface)] border border-[var(--border)] rounded-lg px-3 py-2 text-sm focus:border-[var(--accent)] outline-none">
                                </div>
                                <button type="button" onclick="addPollOption(this)" class="text-xs text-[var(--accent)] font-bold mt-2 hover:underline">+ Add Option</button>
                            </div>
                            <button type="submit" name="create_post" class="w-full btn btn-primary justify-center py-3 mt-4 shadow-lg">Publish Post <i class="fas fa-paper-plane ml-2"></i></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function showToast(msg, type = 'success') {
            const div = document.createElement('div');
            div.className = 'toast';
            div.innerHTML = `<i class="fas fa-info-circle text-[var(--accent)]"></i> <span>${msg}</span>`;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 3000);
        }

        function toggleComments(id) {
            const el = document.getElementById('comments-' + id);
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) el.querySelector('input')?.focus();
        }

        function addPollOption(btn) {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'poll_options[]';
            input.placeholder = 'New Option';
            input.className = 'w-full bg-[var(--surface)] border border-[var(--border)] rounded-lg px-3 py-2 text-sm focus:border-[var(--accent)] outline-none mt-2';
            btn.previousElementSibling.appendChild(input);
        }

        async function apiReq(data) {
            const fd = new FormData();
            for (let k in data) fd.append(k, data[k]);
            try {
                const res = await fetch('posts', {
                    method: 'POST',
                    body: fd
                });
                return await res.json();
            } catch (e) {
                return {
                    success: false,
                    message: 'Network error'
                };
            }
        }

        async function likePost(pid, btn) {
            const res = await apiReq({
                action: 'like_post',
                post_id: pid
            });
            if (res.success) {
                btn.querySelector('span').innerText = res.likeCount;
                const i = btn.querySelector('i');
                if (res.liked) {
                    i.className = 'fas text-[var(--error)] fa-heart text-xl transition';
                } else {
                    i.className = 'far text-[var(--text-tertiary)] group-hover:text-[var(--error)] fa-heart text-xl transition';
                }
            } else showToast(res.message);
        }

        async function postComment(e, pid) {
            e.preventDefault();
            const input = e.target.comment_text;
            const res = await apiReq({
                action: 'add_comment',
                post_id: pid,
                comment_text: input.value
            });
            if (res.success) {
                document.querySelector(`#comments-${pid} .comment-list`).insertAdjacentHTML('beforeend', res.commentHtml);
                input.value = '';
            } else showToast(res.message);
        }

        async function votePoll(pid, pollId, optId) {
            const res = await apiReq({
                action: 'vote_poll',
                post_id: pid,
                poll_id: pollId,
                poll_option_id: optId
            });
            if (res.success) location.reload();
            else showToast(res.message || 'Login to vote');
        }

        async function deletePost(pid) {
            if (!confirm('Delete post?')) return;
            const res = await apiReq({
                action: 'delete_post',
                post_id: pid
            });
            if (res.success) {
                const el = document.getElementById('post-' + pid);
                el.style.transform = 'scale(0.9)';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            } else showToast(res.message);
        }

        AOS.init({
            duration: 600,
            once: true,
            easing: 'ease-out-cubic'
        });
    </script>

</body>

</html>

<?php
include_once 'footer.php';
ob_end_flush();
?>