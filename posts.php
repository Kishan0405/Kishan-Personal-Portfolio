<?php
ob_start(); // Start output buffering at the very beginning

require_once __DIR__ . '/header.php'; // Ensure this file calls session_start() and sets up $pdo

// --- HELPER FUNCTIONS ---

/**
 * Calculates the time elapsed since a given datetime string.
 * @param string $datetime The datetime string (e.g., 'YYYY-MM-DD HH:MM:SS').
 * @return string A human-readable string indicating the time ago.
 */
function time_ago(string $datetime): string
{
    // Ensure the input datetime is interpreted as UTC, if it's stored that way.
    // Or, if it's stored in a specific timezone, handle that consistently.
    // For simplicity, assuming the database stores UTC and strtotime handles it.
    $time = strtotime($datetime);
    if ($time === false) {
        return "Invalid date/time"; // Handle cases where strtotime fails
    }
    $now = time();
    $seconds = $now - $time;

    if ($seconds < 60) { // Less than 1 minute (0-59 seconds)
        return "just now";
    } elseif ($seconds < 3600) { // Less than 1 hour (60 seconds to 59 minutes)
        $minutes = floor($seconds / 60);
        return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
    } elseif ($seconds < 86400) { // Less than 1 day (1 hour to 23 hours)
        $hours = floor($seconds / 3600);
        return $hours == 1 ? "1 hour ago" : "$hours hours ago";
    } elseif ($seconds < 604800) { // Less than 7 days (1 day to 6 days)
        $days = floor($seconds / 86400);
        return $days == 1 ? "1 day ago" : "$days days ago";
    } elseif ($seconds < 2592000) { // Less than approx 1 month (7 days to 29 days) (using 30 days for month approx)
        $weeks = floor($seconds / 604800);
        return $weeks == 1 ? "1 week ago" : "$weeks weeks ago";
    } elseif ($seconds < 31536000) { // Less than 1 year (approx 1 month to 11 months)
        $months = floor($seconds / 2592000); // Approx. seconds in 30 days (30 * 24 * 3600)
        return $months == 1 ? "1 month ago" : "$months months ago";
    } else { // 1 year or more
        $years = floor($seconds / 31536000); // Approx. seconds in 365 days (365 * 24 * 3600)
        return $years == 1 ? "1 year ago" : "$years years ago";
    }
}

/**
 * Converts a datetime string from one timezone to another.
 * @param string $datetimeString The input datetime string.
 * @param string $fromTimezone The original timezone of the input datetime (e.g., 'Asia/Kolkata').
 * @param string $toTimezone The target timezone (e.g., 'UTC').
 * @param string $format The desired output format (default 'Y-m-d H:i:s').
 * @return string The converted datetime string, or original if conversion fails.
 */
function convertTimezone(string $datetimeString, string $fromTimezone, string $toTimezone, string $format = 'Y-m-d H:i:s'): string
{
    try {
        $dateTime = new DateTime($datetimeString, new DateTimeZone($fromTimezone));
        $dateTime->setTimezone(new DateTimeZone($toTimezone));
        return $dateTime->format($format);
    } catch (Exception $e) {
        error_log("Timezone conversion error: " . $e->getMessage());
        return $datetimeString; // Return original on error
    }
}


// --- USER & PERMISSIONS SETUP ---

$userLoggedIn = isset($_SESSION['user_id']);
$isAdmin = ($userLoggedIn && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
$currentUserId = $_SESSION['user_id'] ?? 0;
// Default values for guest or if user data fetch fails
$currentUserProfilePicture = 'https://api.dicebear.com/7.x/initials/svg?seed=Guest&backgroundColor=00d4ff,00b8e6,7c3aed&textColor=ffffff';
$currentUserName = 'Guest';

// Ensure $pdo is available from header.php
if (!isset($pdo) || !$pdo instanceof PDO) {
    // Fallback or error handling if PDO is not initialized
    // In a real application, you might redirect or show a critical error page.
    die("Database connection not available. Please check header.php and ensure PDO is initialized.");
}

if ($userLoggedIn) {
    try {
        $stmtUser = $pdo->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
        $stmtUser->execute([$currentUserId]);
        $currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if ($currentUser) {
            $currentUserName = $currentUser['username'];
            // Generate dicebear URL if profile_picture is not set or empty
            $currentUserProfilePicture = !empty($currentUser['profile_picture']) ? htmlspecialchars($currentUser['profile_picture']) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($currentUser['username']) . '&backgroundColor=00d4ff,00b8e6,7c3aed&textColor=ffffff';
        }
    } catch (PDOException $e) {
        error_log("Error fetching current user data: " . $e->getMessage());
        // Potentially set userLoggedIn to false or redirect to login
        $userLoggedIn = false;
        $currentUserName = 'Guest';
        // Add an error message for the user if necessary
        $errors[] = "Could not retrieve user data. Some features might be limited.";
    }
}

$errors = [];

// --- FORM & ACTION HANDLING ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Asynchronous actions (likes, comments, etc.)
    if (isset($_POST['action'])) {
        // Clear any output that might have been buffered so far (e.g., from includes).
        // This ensures our response is pure JSON.
        if (ob_get_level() > 0) {
            ob_clean(); // Clears output buffer
        }

        header('Content-Type: application/json');

        if (!$userLoggedIn) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']);
            exit();
        }

        $action = $_POST['action'];
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

        // For actions that don't necessarily require a post_id initially (like vote_poll where poll_id is used)
        if (!$postId && !in_array($action, ['vote_poll'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid Post ID.']);
            exit();
        }

        switch ($action) {
            case 'like_post':
                try {
                    $stmt = $pdo->prepare("SELECT id FROM post_likes WHERE user_id = ? AND post_id = ?");
                    $stmt->execute([$currentUserId, $postId]);
                    $is_liked = $stmt->fetch();

                    if ($is_liked) {
                        $stmt = $pdo->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?");
                        $stmt->execute([$currentUserId, $postId]);
                        $message = 'Post unliked';
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)");
                        $stmt->execute([$currentUserId, $postId]);
                        $message = 'Post liked!';
                    }

                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ?");
                    $stmt_count->execute([$postId]);
                    $likeCount = $stmt_count->fetchColumn();

                    echo json_encode(['success' => true, 'liked' => !$is_liked, 'likeCount' => (int)$likeCount, 'message' => $message]);
                } catch (PDOException $e) {
                    error_log("Like/Unlike Error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Database error during like/unlike.']);
                }
                exit();

            case 'add_comment':
                $commentText = trim($_POST['comment_text'] ?? '');
                if (empty($commentText)) {
                    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
                    exit();
                }

                try {
                    // Get current time in IST
                    $nowIST = (new DateTime('now', new DateTimeZone('Asia/Kolkata')))->format('Y-m-d H:i:s');
                    // Convert IST to UTC for database storage
                    $createdAtUTC = convertTimezone($nowIST, 'Asia/Kolkata', 'UTC');

                    // Insert comment with current timestamp (UTC)
                    $stmt = $pdo->prepare("INSERT INTO post_comments (user_id, post_id, comment_text, created_at) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$currentUserId, $postId, $commentText, $createdAtUTC]);
                    $commentId = $pdo->lastInsertId();

                    // Fetch the *actual* created_at timestamp from the database (which should be UTC)
                    $stmtCommentTime = $pdo->prepare("SELECT created_at FROM post_comments WHERE id = ?");
                    $stmtCommentTime->execute([$commentId]);
                    $newCommentCreatedAtUTC = $stmtCommentTime->fetchColumn(); // This is UTC

                    // Convert UTC timestamp back to local time (e.g., IST) for display
                    $newCommentCreatedAtDisplay = convertTimezone($newCommentCreatedAtUTC, 'UTC', 'Asia/Kolkata'); // Or user's preferred timezone

                    // HTML for the new comment to be appended by JS
                    $newCommentHtml = '
                        <div class="flex items-start space-x-3 animate-fade-in">
                            <img src="' . htmlspecialchars($currentUserProfilePicture) . '" alt="Profile picture" class="w-9 h-9 rounded-full object-cover border-2 border-[var(--accent)]">
                            <div class="flex-1 bg-[var(--bg-tertiary)] rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-sm text-[var(--text-primary)]">' . htmlspecialchars($currentUserName) . '</span>
                                    <span class="text-xs text-[var(--text-tertiary)]">' . time_ago($newCommentCreatedAtDisplay) . '</span>
                                </div>
                                <p class="text-sm mt-1 text-[var(--text-secondary)] whitespace-pre-wrap">' . htmlspecialchars($commentText) . '</p>
                            </div>
                        </div>';

                    echo json_encode(['success' => true, 'commentHtml' => $newCommentHtml, 'message' => 'Comment added successfully!']);
                } catch (PDOException $e) {
                    error_log("Add Comment Error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Database error when adding comment.']);
                }
                exit();

            case 'vote_poll':
                $pollId = filter_input(INPUT_POST, 'poll_id', FILTER_VALIDATE_INT);
                $optionId = filter_input(INPUT_POST, 'poll_option_id', FILTER_VALIDATE_INT);

                if (!$pollId || !$optionId) {
                    echo json_encode(['success' => false, 'message' => 'Invalid poll or option ID.']);
                    exit();
                }

                try {
                    // Check if the user is voting on an option that belongs to the poll
                    $stmtCheckOption = $pdo->prepare("SELECT id FROM poll_options WHERE id = ? AND poll_id = ?");
                    $stmtCheckOption->execute([$optionId, $pollId]);
                    if (!$stmtCheckOption->fetch()) {
                        echo json_encode(['success' => false, 'message' => 'Invalid poll option for this poll.']);
                        exit();
                    }

                    $pdo->beginTransaction();
                    // UPSERT: Insert if not exists, update if exists
                    $stmt = $pdo->prepare("INSERT INTO poll_votes (user_id, poll_id, poll_option_id) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE poll_option_id = VALUES(poll_option_id)");
                    $stmt->execute([$currentUserId, $pollId, $optionId]);
                    $pdo->commit();

                    // Fetch updated poll data to send back to the client
                    // Recalculate all poll options' vote counts and percentages
                    $stmt_poll_results = $pdo->prepare("
                        SELECT po.id as option_id, COUNT(pv.id) as vote_count
                        FROM poll_options po
                        LEFT JOIN poll_votes pv ON po.id = pv.poll_option_id
                        WHERE po.poll_id = ?
                        GROUP BY po.id
                        ORDER BY po.id
                    ");
                    $stmt_poll_results->execute([$pollId]);
                    $rawResults = $stmt_poll_results->fetchAll(PDO::FETCH_ASSOC);

                    $totalVotes = 0;
                    foreach ($rawResults as $row) {
                        $totalVotes += (int)$row['vote_count'];
                    }

                    $optionsData = [];
                    foreach ($rawResults as $row) {
                        $optionsData[] = [
                            'id' => (int)$row['option_id'],
                            'vote_count' => (int)$row['vote_count'],
                            'percentage' => $totalVotes > 0 ? round(((int)$row['vote_count'] / $totalVotes) * 100) : 0
                        ];
                    }

                    $response = [
                        'success' => true,
                        'message' => 'Vote cast successfully!',
                        'pollData' => [
                            'totalVotes' => (int)$totalVotes,
                            'userVotedOption' => (int)$optionId, // This is the option the current user voted for
                            'options' => $optionsData
                        ]
                    ];

                    echo json_encode($response);
                } catch (PDOException $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    error_log("Poll Vote Error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Database error during poll vote.']);
                }
                exit();

            case 'delete_post':
                if (!$postId) {
                    echo json_encode(['success' => false, 'message' => 'Invalid Post ID for deletion.']);
                    exit();
                }

                try {
                    // Fetch post info for authorization and to get file path
                    $stmt_check = $pdo->prepare("SELECT user_id, image_path FROM posts WHERE id = ?");
                    $stmt_check->execute([$postId]);
                    $post_to_delete = $stmt_check->fetch(PDO::FETCH_ASSOC);

                    if (!$post_to_delete) {
                        echo json_encode(['success' => false, 'message' => 'Post not found.']);
                        exit();
                    }

                    // Authorization check: User must be an admin OR the owner of the post.
                    if (!$isAdmin && $post_to_delete['user_id'] != $currentUserId) {
                        echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                        exit();
                    }

                    // Proceed with deletion in a transaction
                    $pdo->beginTransaction();

                    // Get poll ID if it exists for this post
                    $stmtPoll = $pdo->prepare("SELECT id FROM polls WHERE post_id = ?");
                    $stmtPoll->execute([$postId]);
                    $poll = $stmtPoll->fetch(PDO::FETCH_ASSOC);

                    if ($poll) {
                        $pollId = $poll['id'];
                        // Delete all related poll data (due to CASCADE, deleting poll_options will also delete poll_votes)
                        // It's safer to explicitly delete poll_votes first if poll_options ON DELETE CASCADE is not set for poll_votes
                        // But in your schema, poll_votes references poll_options, and poll_options references polls, all ON DELETE CASCADE.
                        // So deleting from polls will cascade to poll_options and poll_votes.
                        // However, explicit deletion might be desired for clarity or if cascade rules change.
                        // For safety, let's explicitly delete votes and options if poll exists:
                        $pdo->prepare("DELETE FROM poll_votes WHERE poll_id = ?")->execute([$pollId]);
                        $pdo->prepare("DELETE FROM poll_options WHERE poll_id = ?")->execute([$pollId]);
                        $pdo->prepare("DELETE FROM polls WHERE id = ?")->execute([$pollId]);
                    }

                    // Delete related comments and likes (these tables directly reference posts)
                    $pdo->prepare("DELETE FROM post_likes WHERE post_id = ?")->execute([$postId]);
                    $pdo->prepare("DELETE FROM post_comments WHERE post_id = ?")->execute([$postId]);

                    // Delete the post itself
                    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$postId]);

                    // Delete the physical image file if it exists
                    if (!empty($post_to_delete['image_path']) && file_exists(__DIR__ . '/' . $post_to_delete['image_path'])) {
                        unlink(__DIR__ . '/' . $post_to_delete['image_path']);
                    }

                    $pdo->commit();
                    echo json_encode(['success' => true, 'message' => 'Post deleted successfully.']);
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    error_log("Post Deletion Error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Failed to delete post due to a server error.']);
                }
                exit();
        }
    }

    // Handle regular form submission for creating a post (only if not an AJAX action)
    if ($isAdmin && isset($_POST['create_post'])) {
        $content = trim($_POST['content'] ?? '');
        $poll_question = trim($_POST['poll_question'] ?? '');
        // Filter out empty options and re-index the array
        $poll_options = array_values(array_filter(array_map('trim', $_POST['poll_options'] ?? [])));

        // Basic validation for post content/image/poll
        if (empty($content) && empty($poll_question) && (!isset($_FILES['post_image']) || $_FILES['post_image']['error'] !== UPLOAD_ERR_OK)) {
            $errors[] = "Post content, an image, or a poll must be provided.";
        }

        if (!empty($poll_question) && count($poll_options) < 2) {
            $errors[] = "A poll requires a question and at least two options.";
        } elseif (empty($poll_question) && count($poll_options) >= 2) {
            $errors[] = "Poll options provided but no question.";
        }


        $imagePath = null;
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5 MB

            if (!in_array($_FILES['post_image']['type'], $allowedTypes)) {
                $errors[] = "Invalid image file type. Only JPG, PNG, GIF, and WEBP are allowed.";
            } elseif ($_FILES['post_image']['size'] > $maxSize) {
                $errors[] = "Image file is too large. Maximum size is 5MB.";
            } else {
                $uploadDir = 'uploadsposts/';
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                        $errors[] = "Failed to create upload directory: " . $uploadDir;
                    }
                }
                if (empty($errors)) { // Only proceed if mkdir was successful
                    // Generate a unique file name to prevent overwrites and security issues
                    $fileExtension = pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
                    $imageName = uniqid('post-', true) . '.' . $fileExtension;
                    $targetFile = $uploadDir . $imageName;
                    if (move_uploaded_file($_FILES['post_image']['tmp_name'], $targetFile)) {
                        $imagePath = $targetFile;
                    } else {
                        $errors[] = "Failed to upload image. Check folder permissions or server configuration.";
                    }
                }
            }
        }

        if (empty($errors)) {
            $pdo->beginTransaction();
            try {
                // Get current time in IST
                $nowIST = (new DateTime('now', new DateTimeZone('Asia/Kolkata')))->format('Y-m-d H:i:s');
                // Convert IST to UTC for database storage
                $createdAtUTC = convertTimezone($nowIST, 'Asia/Kolkata', 'UTC');

                // Insert post with current timestamp (UTC)
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image_path, created_at) VALUES (?, ?, ?, ?)");
                $stmt->execute([$currentUserId, $content, $imagePath, $createdAtUTC]);
                $postId = $pdo->lastInsertId();

                if (!empty($poll_question) && count($poll_options) >= 2) {
                    $stmt = $pdo->prepare("INSERT INTO polls (post_id, question) VALUES (?, ?)");
                    $stmt->execute([$postId, $poll_question]);
                    $pollId = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (?, ?)");
                    foreach ($poll_options as $option) {
                        $stmt->execute([$pollId, $option]);
                    }
                }
                $pdo->commit();
                // Redirect to avoid form resubmission on refresh
                header("Location: posts");
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                // If image was uploaded, delete it to prevent orphaned files
                if ($imagePath && file_exists(__DIR__ . '/' . $imagePath)) {
                    unlink(__DIR__ . '/' . $imagePath);
                }
                error_log("Post Creation Error: " . $e->getMessage());
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}

// --- DATA FETCHING for PAGE LOAD ---

// Ensure $pdo is available before fetching data for display
if (!isset($pdo) || !$pdo instanceof PDO) {
    // This case should ideally be handled earlier or redirected to an error page
    $posts = [];
    $likesByPost = [];
    $commentsByPost = [];
    $pollDataByPost = [];
    // Already added an error message for general DB connection issue
} else {
    try {
        // MODIFIED: Added p.user_id to the SELECT statement
        $stmt_posts = $pdo->prepare("
            SELECT p.id, p.user_id, p.content, p.image_path, p.created_at, u.username, u.profile_picture
            FROM posts p JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
        ");
        $stmt_posts->execute();
        $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

        $postIds = array_column($posts, 'id');
        $likesByPost = [];
        $commentsByPost = [];
        $pollDataByPost = [];

        if (!empty($postIds)) {
            $placeholders = implode(',', array_fill(0, count($postIds), '?'));

            // Fetch Likes
            $stmt_likes = $pdo->prepare("SELECT post_id, user_id FROM post_likes WHERE post_id IN ($placeholders)");
            $stmt_likes->execute($postIds);
            while ($like = $stmt_likes->fetch(PDO::FETCH_ASSOC)) {
                $likesByPost[$like['post_id']][] = $like['user_id'];
            }

            // Fetch Comments
            $stmt_comments = $pdo->prepare("SELECT c.*, u.username, u.profile_picture FROM post_comments c JOIN users u ON c.user_id = u.id WHERE c.post_id IN ($placeholders) ORDER BY c.created_at ASC");
            $stmt_comments->execute($postIds);
            while ($comment = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
                $commentsByPost[$comment['post_id']][] = $comment;
            }

            // Fetch Polls Data
            $stmt_polls = $pdo->prepare("
                SELECT p.post_id, p.id as poll_id, p.question, po.id as option_id, po.option_text,
                    GROUP_CONCAT(DISTINCT pv.user_id) as voters_list -- Use DISTINCT to avoid duplicate user_ids if multiple votes recorded
                FROM polls p
                JOIN poll_options po ON p.id = po.poll_id
                LEFT JOIN poll_votes pv ON po.id = pv.poll_option_id
                WHERE p.post_id IN ($placeholders)
                GROUP BY p.post_id, p.id, p.question, po.id, po.option_text
                ORDER BY po.id
            ");
            $stmt_polls->execute($postIds);
            $rawPollData = $stmt_polls->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawPollData as $row) {
                $postId = $row['post_id'];
                if (!isset($pollDataByPost[$postId])) {
                    $pollDataByPost[$postId] = [
                        'id' => $row['poll_id'],
                        'question' => $row['question'],
                        'options' => [],
                        'userVotedOption' => null, // Initialize
                        'totalVotes' => 0
                    ];
                }

                $voters = $row['voters_list'] ? explode(',', $row['voters_list']) : [];
                $voteCount = count($voters);

                $pollDataByPost[$postId]['options'][$row['option_id']] = [
                    'id' => $row['option_id'],
                    'text' => $row['option_text'],
                    'vote_count' => $voteCount
                ];

                $pollDataByPost[$postId]['totalVotes'] += $voteCount;
                if ($userLoggedIn && in_array($currentUserId, $voters)) {
                    $pollDataByPost[$postId]['userVotedOption'] = $row['option_id'];
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Error fetching posts data: " . $e->getMessage());
        $posts = []; // Clear posts if there's a database error
        $errors[] = "Error fetching posts from the database. Please try again later.";
    }
}

// No ob_end_flush() here. The buffer will automatically be flushed at the end of the script,
// ensuring the HTML is sent correctly after all PHP processing.
?>
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Feed - Social Platform</title>
    <meta name="description" content="Engage with the community by creating, liking, and commenting on posts.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/search.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <style>
        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-tertiary: #1a1a1a;
            --surface: #ffffff05;
            --surface-hover: #ffffff0d;
            --border: #ffffff1a;
            --border-hover: #ffffff25;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-tertiary: #707070;
            --accent: #00d4ff;
            --accent-hover: #00b8e6;
            --accent-secondary: #7c3aed;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --glass-bg: rgba(16, 16, 16, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
            --shadow-neon: 0 0 20px rgba(0, 212, 255, 0.2);
            --animated-bg-gradient-1: #10142c;
            --animated-bg-gradient-2: #1c0e2a;
        }

        html.light {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f3f4;
            --surface: #00000005;
            --surface-hover: #0000000a;
            --border: #0000001a;
            --border-hover: #00000025;
            --text-primary: #1a1a1a;
            --text-secondary: #4a4a4a;
            --text-tertiary: #6a6a6a;
            --accent: #0066cc;
            --accent-hover: #0052a3;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(0, 0, 0, 0.1);
            --shadow-neon: 0 0 20px rgba(0, 102, 204, 0.2);
            --animated-bg-gradient-1: #E6E6FA;
            --animated-bg-gradient-2: #F0F8FF;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            background: linear-gradient(-45deg, var(--animated-bg-gradient-1), var(--bg-primary), var(--animated-bg-gradient-2), var(--bg-primary));
            background-size: 500% 500%;
            animation: bg-gradient-animation 20s ease infinite;
            transition: background-color 0.3s, color 0.3s;
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

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--glass-border);
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        .poll-progress {
            background: linear-gradient(90deg, var(--accent), var(--accent-secondary));
            transition: width 0.7s cubic-bezier(0.25, 1, 0.5, 1);
            position: relative;
            will-change: width;
        }

        .poll-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
            box-shadow: 0 4px 15px rgba(0, 212, 255, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
            transform: translateY(-2px);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-hover);
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-20%);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            text-align: center;
        }

        .toast {
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(20px);
            animation: toast-show-hide 5s forwards;
        }

        @keyframes toast-show-hide {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(0);
            }
        }

        html.dark .toast {
            background-color: #E6EDF3;
            color: #161B22;
        }

        @media (max-width: 640px) {
            .toast-container {
                left: 50%;
                right: auto;
                transform: translateX(-50%);
                bottom: 16px;
                width: 100%;
            }

            .toast {
                padding: 12px 16px;
                text-align: center;
                font-size: 16px;
            }
        }
    </style>
</head>

<body class="antialiased text-text-primary">
    <?php
    // Determine the class for the main content column to center it when the admin sidebar isn't present.
    $mainContentClass = $isAdmin ? 'col-span-12 lg:col-span-8' : 'col-span-12 lg:col-span-8 lg:col-start-3';
    ?>

    <main class="main-content-wrapper flex-1 px-4 py-8 lg:ml-60">
        <div id="toastContainer" class="toast-container"></div>

        <div class="max-w-7xl mx-auto grid grid-cols-12 gap-8">

            <div class="<?= $mainContentClass ?>">
                <div id="posts-container" class="space-y-8">
                    <?php if (empty($posts)): ?>
                        <div class="text-center py-20 glass rounded-2xl">
                            <svg class="mx-auto h-12 w-12 text-[var(--text-secondary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-semibold">No posts yet</h3>
                            <p class="mt-1 text-sm text-[var(--text-secondary)]">The feed is empty. Be the first to post!</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($posts as $post) : ?>
                        <?php
                        $like_count = count($likesByPost[$post['id']] ?? []);
                        $user_liked = $userLoggedIn && in_array($currentUserId, $likesByPost[$post['id']] ?? []);
                        $comments = $commentsByPost[$post['id']] ?? [];
                        $comment_count = count($comments);
                        $poll = $pollDataByPost[$post['id']] ?? null;

                        // Convert UTC created_at to IST for display
                        $displayCreatedAt = convertTimezone($post['created_at'], 'UTC', 'Asia/Kolkata');

                        // Ensure profile picture URL for post author
                        $postAuthorProfilePicture = !empty($post['profile_picture']) ? htmlspecialchars($post['profile_picture']) : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($post['username']) . '&backgroundColor=00d4ff,00b8e6,7c3aed&textColor=ffffff';
                        ?>
                        <article id="post-<?= $post['id'] ?>" class="glass rounded-2xl overflow-hidden transition-all duration-300 hover:border-[var(--border-hover)]" data-aos="fade-up">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <img src="<?= $postAuthorProfilePicture ?>" alt="Profile picture" class="w-12 h-12 rounded-full object-cover border-2 border-[var(--accent)]">
                                        <div>
                                            <p class="font-semibold text-lg text-text-primary"><?= htmlspecialchars($post['username']) ?></p>
                                            <p class="text-sm text-text-tertiary"><?= time_ago($displayCreatedAt) ?></p>
                                        </div>
                                    </div>
                                    <?php // Show dropdown if user is admin OR the author of the post
                                    if ($isAdmin || ($userLoggedIn && $post['user_id'] == $currentUserId)): ?>
                                        <div class="relative dropdown">
                                            <button class="text-text-tertiary hover:text-text-primary dropdown-toggle p-2 rounded-full hover:bg-[var(--surface-hover)] transition-colors"><i class="fas fa-ellipsis-h"></i></button>
                                            <div class="dropdown-menu hidden absolute right-0 mt-2 w-44 glass rounded-lg z-10 p-1">
                                                <button data-action="delete-post" data-post-id="<?= $post['id'] ?>" class="w-full text-left px-3 py-2 text-sm text-[var(--danger)] hover:bg-[var(--danger)]/10 flex items-center space-x-2 rounded-md transition-colors">
                                                    <i class="fas fa-trash-alt w-4"></i><span>Delete Post</span>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <p class="text-text-secondary mb-4 whitespace-pre-wrap"><?= htmlspecialchars($post['content']) ?></p>

                                <?php if ($post['image_path']) : ?>
                                    <div class="mt-4 rounded-lg overflow-hidden border border-[var(--border)]">
                                        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post image" class="w-full h-auto object-cover">
                                    </div>
                                <?php endif; ?>

                                <?php if ($poll): ?>
                                    <div class="my-5 border border-[var(--border)] rounded-lg p-4 poll-container bg-[var(--surface)]" data-poll-id="<?= $poll['id'] ?>">
                                        <h4 class="font-semibold text-lg mb-4 text-[var(--text-primary)]"><?= htmlspecialchars($poll['question']) ?></h4>
                                        <?php $hasVoted = $userLoggedIn && $poll['userVotedOption'] !== null; ?>
                                        <form class="space-y-3 poll-form" <?= $hasVoted ? 'style="display: none;"' : '' ?>>
                                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                            <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                                            <?php foreach ($poll['options'] as $option) : ?>
                                                <label class="flex items-center space-x-3 p-3 rounded-lg border border-[var(--border)] hover:bg-[var(--surface-hover)] hover:border-[var(--border-hover)] transition-colors cursor-pointer">
                                                    <input type="radio" name="poll_option_id" value="<?= $option['id'] ?>" class="h-5 w-5 text-[var(--accent)] focus:ring-[var(--accent-hover)] bg-transparent border-[var(--border)]" <?= ($userLoggedIn ? '' : 'disabled') ?>>
                                                    <span class="font-medium text-[var(--text-secondary)]"><?= htmlspecialchars($option['text']) ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                            <button type="submit" class="btn-vote btn btn-primary mt-2" <?= ($userLoggedIn && !$hasVoted) ? '' : 'disabled' ?>>Vote</button>
                                            <?php if (!$userLoggedIn): ?>
                                                <p class="text-sm text-red-400 mt-2">Log in to vote.</p>
                                            <?php endif; ?>
                                        </form>
                                        <div class="poll-results space-y-4" <?= !$hasVoted ? 'style="display: none;"' : '' ?>>
                                            <?php foreach ($poll['options'] as $option) : ?>
                                                <?php
                                                $percentage = $poll['totalVotes'] > 0 ? round(($option['vote_count'] / $poll['totalVotes']) * 100) : 0;
                                                $isVotedByCurrentUser = ($userLoggedIn && $poll['userVotedOption'] == $option['id']);
                                                ?>
                                                <div data-option-id="<?= $option['id'] ?>">
                                                    <div class="flex justify-between items-center text-sm font-medium mb-1">
                                                        <span class="flex items-center gap-2 option-text <?= $isVotedByCurrentUser ? 'text-[var(--accent)] font-bold' : 'text-[var(--text-secondary)]' ?>">
                                                            <?= htmlspecialchars($option['text']) ?>
                                                            <?php if ($isVotedByCurrentUser): ?><i class="fas fa-check-circle"></i><?php endif; ?>
                                                        </span>
                                                        <span class="text-text-tertiary percentage-label"><?= $percentage ?>%</span>
                                                    </div>
                                                    <div class="w-full bg-[var(--bg-tertiary)] rounded-full h-2">
                                                        <div class="poll-progress h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <p class="text-xs text-text-tertiary mt-3 total-votes"><?= $poll['totalVotes'] ?> vote<?= $poll['totalVotes'] != 1 ? 's' : '' ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="flex items-center space-x-2 border-t border-[var(--border)] pt-4 mt-4">
                                    <button data-action="like-post" data-post-id="<?= $post['id'] ?>" class="flex items-center text-text-secondary hover:text-[var(--accent)] transition-colors disabled:opacity-50 disabled:cursor-not-allowed like-button <?= $user_liked ? 'text-[var(--accent)]' : '' ?> px-3 py-2 rounded-lg hover:bg-[var(--surface-hover)]" <?= !$userLoggedIn ? 'disabled' : '' ?>>
                                        <i class="fa-heart mr-2 <?= $user_liked ? 'fas' : 'far' ?> fa-lg"></i>
                                        <span class="like-count font-medium text-sm"><?= $like_count ?></span>
                                    </button>
                                    <button data-action="toggle-comments" data-post-id="<?= $post['id'] ?>" class="flex items-center text-text-secondary hover:text-[var(--accent)] transition-colors px-3 py-2 rounded-lg hover:bg-[var(--surface-hover)]">
                                        <i class="far fa-comment-dots fa-lg mr-2"></i>
                                        <span class="comment-count font-medium text-sm"><?= $comment_count ?></span>
                                    </button>
                                </div>

                                <div class="comments-section hidden mt-4 border-t border-[var(--border)] pt-4">
                                    <?php if ($userLoggedIn) : ?>
                                        <form class="comment-form flex items-start space-x-3 mb-6">
                                            <img src="<?= htmlspecialchars($currentUserProfilePicture) ?>" alt="Your avatar" class="w-10 h-10 rounded-full object-cover">
                                            <div class="flex-1">
                                                <textarea name="comment_text" rows="1" placeholder="Add a comment..." required class="w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-text-primary placeholder-text-tertiary p-3 resize-none transition-all" oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"></textarea>
                                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                <div class="text-right mt-2">
                                                    <button type="submit" class="btn btn-primary" disabled>Comment</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <p class="text-center text-text-secondary text-sm mb-4">You must be logged in to comment.</p>
                                    <?php endif; ?>
                                    <div class="comments-list space-y-4">
                                        <?php foreach ($comments as $comment) : ?>
                                            <?php
                                            // Convert UTC comment created_at to IST for display
                                            $displayCommentCreatedAt = convertTimezone($comment['created_at'], 'UTC', 'Asia/Kolkata');
                                            ?>
                                            <div class="flex items-start space-x-3">
                                                <img src="<?= htmlspecialchars($comment['profile_picture'] ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($comment['username']) . '&backgroundColor=00d4ff,00b8e6,7c3aed&textColor=ffffff') ?>" alt="Profile picture" class="w-9 h-9 rounded-full object-cover border-2 border-[var(--accent)]">
                                                <div class="flex-1 bg-[var(--bg-tertiary)] rounded-lg p-3">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-semibold text-sm text-[var(--text-primary)]"><?= htmlspecialchars($comment['username']) ?></span>
                                                        <span class="text-xs text-[var(--text-tertiary)]"><?= time_ago($displayCommentCreatedAt) ?></span>
                                                    </div>
                                                    <p class="text-sm mt-1 text-[var(--text-secondary)] whitespace-pre-wrap"><?= htmlspecialchars($comment['comment_text']) ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($isAdmin) : ?>
                <aside class="col-span-12 lg:col-span-4">
                    <div class="lg:sticky top-8 self-start glass rounded-2xl p-6">
                        <h2 class="text-2xl font-bold mb-5 text-[var(--text-primary)] font-space">Create a Post</h2>
                        <?php if (!empty($errors)) : ?>
                            <div class="bg-red-500/10 border-l-4 border-[var(--danger)] text-red-300 p-4 mb-4 rounded">
                                <?php foreach ($errors as $error) : ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                            <div>
                                <textarea id="content" name="content" rows="4" class="w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-[var(--text-primary)] placeholder-[var(--text-tertiary)] p-3 transition-colors" placeholder="What's on your mind?"></textarea>
                            </div>
                            <div>
                                <label for="post_image" class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-[var(--border)] text-sm font-medium rounded-lg text-[var(--text-secondary)] hover:border-[var(--accent)] hover:text-[var(--accent)] cursor-pointer transition-colors">
                                    <i class="fas fa-image mr-2"></i>
                                    <span id="image-label">Add an Image</span>
                                </label>
                                <input type="file" id="post_image" name="post_image" accept="image/png, image/jpeg, image/gif, image/webp" class="hidden">
                                <img id="image-preview" src="" alt="Image preview" class="hidden rounded-lg mt-4 max-h-48 w-auto mx-auto border border-[var(--border)]" />
                            </div>
                            <div class="border-t border-[var(--border)] pt-5">
                                <h3 class="text-lg font-semibold text-[var(--text-primary)]">Create a Poll <span class="text-sm font-normal text-[var(--text-tertiary)]">(Optional)</span></h3>
                                <input type="text" name="poll_question" placeholder="Poll Question" class="mt-3 w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-[var(--text-primary)] placeholder-[var(--text-tertiary)] p-3 transition-colors">
                                <div id="poll-options-container" class="mt-2 space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="poll_options[]" placeholder="Option 1" class="w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-[var(--text-primary)] placeholder-[var(--text-tertiary)] p-3 transition-colors">
                                        <button type="button" class="remove-option-btn text-[var(--danger)] font-bold text-xl hover:text-[var(--danger-hover)] transition-colors p-1" title="Remove option">×</button>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="poll_options[]" placeholder="Option 2" class="w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-[var(--text-primary)] placeholder-[var(--text-tertiary)] p-3 transition-colors">
                                        <button type="button" class="remove-option-btn text-[var(--danger)] font-bold text-xl hover:text-[var(--danger-hover)] transition-colors p-1" title="Remove option">×</button>
                                    </div>
                                </div>
                                <button type="button" id="add-poll-option" class="mt-2 text-sm text-[var(--accent)] hover:underline">+ Add Option</button>
                            </div>
                            <button type="submit" name="create_post" class="w-full btn btn-primary justify-center text-base py-2.5">Post</button>
                        </form>
                    </div>
                </aside>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                duration: 600,
                once: true,
                easing: 'ease-out-cubic'
            });

            const toastContainer = document.getElementById('toastContainer');

            // This function creates and displays the toast notifications
            function showToast(message, type = 'success') {
                if (!toastContainer) return;

                const toast = document.createElement('div');
                toast.classList.add('toast');
                toast.classList.add(type === 'success' ? 'bg-green-500/80' : 'bg-red-500/80'); // Tailwind classes for visual distinction
                toast.style.backdropFilter = 'blur(10px)'; // Apply glass effect via JS
                toast.style.color = 'white'; // Ensure text is visible on colored toast

                toast.innerHTML = `<span>${message}</span>`;

                toastContainer.appendChild(toast);

                // Use a short timeout to allow reflow before starting animation
                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                }, 10); // Small delay before animation starts

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px) scale(0.9)'; // Animate out
                    setTimeout(() => toast.remove(), 300); // Remove after animation
                }, 4000); // Total display time 4 seconds (animation is 4s)
            }

            const postsContainer = document.getElementById('posts-container');

            if (postsContainer) {
                postsContainer.addEventListener('click', (e) => {
                    const button = e.target.closest('button[data-action]');
                    if (!button) return;

                    // Prevent default button behavior, especially for any buttons inside forms
                    e.preventDefault();

                    const action = button.dataset.action;
                    const postId = button.dataset.postId;

                    switch (action) {
                        case 'like-post':
                            handleLike(postId, button);
                            break;
                        case 'toggle-comments':
                            const commentsSection = button.closest('article').querySelector('.comments-section');
                            commentsSection.classList.toggle('hidden');
                            if (!commentsSection.classList.contains('hidden')) {
                                // Focus on textarea if comments section is revealed
                                commentsSection.querySelector('textarea')?.focus();
                            }
                            break;
                        case 'delete-post':
                            handleDelete(postId);
                            break;
                    }
                });
            }

            // Dropdown menu logic for post options (admin)
            document.querySelectorAll('.dropdown-toggle').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent document click listener from immediately closing it
                    const menu = button.nextElementSibling;
                    const isHidden = menu.classList.contains('hidden');
                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
                    // Toggle current dropdown
                    if (isHidden) menu.classList.remove('hidden');
                });
            });

            // Close dropdown if click is outside any dropdown
            window.addEventListener('click', (e) => {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
                }
            });

            async function handleLike(postId, button) {
                // PHP side also checks this, but good for immediate client-side feedback without a server roundtrip
                if (!<?= json_encode($userLoggedIn) ?>) {
                    showToast('You must be logged in to like posts.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('action', 'like_post');
                formData.append('post_id', postId);

                try {
                    const response = await fetch('posts', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        const icon = button.querySelector('i');
                        const countSpan = button.querySelector('.like-count');
                        button.classList.toggle('text-[var(--accent)]', data.liked);
                        icon.classList.toggle('fas', data.liked);
                        icon.classList.toggle('far', !data.liked);
                        countSpan.textContent = data.likeCount;
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'An error occurred during like/unlike.', 'error');
                    }
                } catch (error) {
                    console.error('Error liking post:', error);
                    showToast('Network error or server unavailable.', 'error');
                }
            }

            async function handleDelete(postId) {
                if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) return;

                const formData = new FormData();
                formData.append('action', 'delete_post');
                formData.append('post_id', postId);

                try {
                    const response = await fetch('posts', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        showToast(data.message, 'success');
                        const postElement = document.getElementById(`post-${postId}`);
                        if (postElement) {
                            postElement.style.transition = 'opacity 0.5s ease, transform 0.5s ease, margin 0.5s ease, height 0.5s ease'; // Added height transition
                            postElement.style.opacity = '0';
                            postElement.style.transform = 'scale(0.95)';
                            // Measure height before collapsing to provide smooth transition
                            const originalHeight = postElement.offsetHeight;
                            postElement.style.height = `${originalHeight}px`; // Fix height
                            setTimeout(() => {
                                postElement.style.height = '0'; // Collapse height
                                postElement.style.marginTop = `0px`; // Reset margin
                                postElement.style.paddingTop = '0';
                                postElement.style.paddingBottom = '0';
                                setTimeout(() => postElement.remove(), 500); // Remove after collapse animation
                            }, 500); // Wait for fade/scale animation to finish before collapsing
                        }
                    } else {
                        showToast(data.message || 'Failed to delete post.', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting post:', error);
                    showToast('Network error or server unavailable.', 'error');
                }
            }

            document.querySelectorAll('.comment-form').forEach(form => {
                const textarea = form.querySelector('textarea');
                const submitButton = form.querySelector('button[type="submit"]');

                // Function to enable/disable submit button based on textarea content
                const checkCommentInput = () => {
                    submitButton.disabled = textarea.value.trim().length === 0;
                };

                textarea.addEventListener('input', checkCommentInput);
                // Initial check in case there's pre-filled content (though usually not for new comments)
                checkCommentInput();

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (!<?= json_encode($userLoggedIn) ?>) {
                        showToast('You must be logged in to comment.', 'error');
                        return;
                    }
                    if (textarea.value.trim().length === 0) {
                        showToast('Comment cannot be empty.', 'error');
                        return;
                    }

                    const formData = new FormData(form);
                    formData.append('action', 'add_comment');

                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Posting...';

                    try {
                        const response = await fetch('posts', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            const commentsList = form.closest('.comments-section').querySelector('.comments-list');
                            commentsList.insertAdjacentHTML('beforeend', data.commentHtml); // Append new comment
                            form.reset(); // Clear textarea
                            textarea.style.height = 'auto'; // Reset textarea height
                            submitButton.disabled = true; // Disable until new input

                            const postCard = form.closest('article');
                            const commentCountSpan = postCard.querySelector('.comment-count');
                            commentCountSpan.textContent = parseInt(commentCountSpan.textContent) + 1; // Increment comment count
                        } else {
                            showToast(data.message || 'Failed to post comment.', 'error');
                        }
                    } catch (error) {
                        console.error('Error adding comment:', error);
                        showToast('Network error or server unavailable.', 'error');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Comment';
                    }
                });
            });

            document.querySelectorAll('.poll-form').forEach(form => {
                const voteButton = form.querySelector('.btn-vote');
                const radioButtons = form.querySelectorAll('input[name="poll_option_id"]');

                // Enable/disable vote button based on selection
                const checkPollSelection = () => {
                    const hasSelection = Array.from(radioButtons).some(radio => radio.checked);
                    // Only enable if user is logged in AND an option is selected
                    voteButton.disabled = !hasSelection || !<?= json_encode($userLoggedIn) ?>;
                };

                radioButtons.forEach(radio => radio.addEventListener('change', checkPollSelection));
                checkPollSelection(); // Initial check on page load

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (!<?= json_encode($userLoggedIn) ?>) {
                        showToast('You must be logged in to vote.', 'error');
                        return;
                    }
                    if (!Array.from(radioButtons).some(radio => radio.checked)) {
                        showToast('Please select an option to vote.', 'error');
                        return;
                    }

                    const formData = new FormData(form);
                    formData.append('action', 'vote_poll');

                    voteButton.disabled = true;
                    voteButton.innerHTML = 'Voting...';

                    try {
                        const response = await fetch('posts', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            updatePollView(form.closest('.poll-container'), data.pollData);
                        } else {
                            showToast(data.message || 'Failed to vote.', 'error');
                        }
                    } catch (error) {
                        console.error('Error voting poll:', error);
                        showToast('Network error or server unavailable.', 'error');
                    } finally {
                        voteButton.disabled = false;
                        voteButton.innerHTML = 'Vote';
                    }
                });
            });

            function updatePollView(pollContainer, pollData) {
                const pollForm = pollContainer.querySelector('.poll-form');
                const pollResults = pollContainer.querySelector('.poll-results');

                const totalVotesEl = pollResults.querySelector('.total-votes');
                totalVotesEl.textContent = `${pollData.totalVotes} vote${pollData.totalVotes !== 1 ? 's' : ''}`;

                pollData.options.forEach(option => {
                    const optionEl = pollResults.querySelector(`div[data-option-id='${option.id}']`);
                    if (optionEl) {
                        optionEl.querySelector('.percentage-label').textContent = `${option.percentage}%`;
                        optionEl.querySelector('.poll-progress').style.width = `${option.percentage}%`;

                        const optionTextEl = optionEl.querySelector('.option-text');
                        // Remove previous checkmark and styles
                        optionTextEl.classList.remove('text-[var(--accent)]', 'font-bold');
                        optionTextEl.classList.add('text-[var(--text-secondary)]');

                        const existingCheck = optionTextEl.querySelector('i.fa-check-circle');
                        if (existingCheck) existingCheck.remove();

                        // Add new checkmark and styles if this is the user's selected option
                        if (option.id === pollData.userVotedOption) {
                            optionTextEl.classList.add('text-[var(--accent)]', 'font-bold');
                            optionTextEl.classList.remove('text-[var(--text-secondary)]');
                            optionTextEl.insertAdjacentHTML('beforeend', ' <i class="fas fa-check-circle"></i>');
                        }
                    }
                });

                pollForm.style.display = 'none';
                pollResults.style.display = 'block';
            }

            const adminForm = document.querySelector('aside form');
            if (adminForm) {
                const postImageInput = document.getElementById('post_image');
                postImageInput.addEventListener('change', function() {
                    const imageLabel = document.getElementById('image-label');
                    const imagePreview = document.getElementById('image-preview');
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(this.files[0]);
                        imageLabel.textContent = this.files[0].name;
                    } else {
                        imageLabel.textContent = 'Add an Image';
                        imagePreview.classList.add('hidden');
                    }
                });

                const pollOptionsContainer = document.getElementById('poll-options-container');
                document.getElementById('add-poll-option').addEventListener('click', () => {
                    const count = pollOptionsContainer.children.length + 1;
                    const newOptionWrapper = document.createElement('div');
                    newOptionWrapper.className = 'flex items-center space-x-2 animate-fade-in';
                    newOptionWrapper.innerHTML = `
                        <input type="text" name="poll_options[]" placeholder="Option ${count}" class="w-full rounded-lg bg-[var(--bg-tertiary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-0 text-[var(--text-primary)] placeholder-[var(--text-tertiary)] p-3 transition-colors">
                        <button type="button" class="remove-option-btn text-[var(--danger)] font-bold text-xl hover:text-[var(--danger-hover)] transition-colors p-1" title="Remove option">×</button>
                    `;
                    pollOptionsContainer.appendChild(newOptionWrapper);
                });

                pollOptionsContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('remove-option-btn')) {
                        // Ensure there are at least 2 options remaining after removal
                        if (pollOptionsContainer.children.length > 2) {
                            e.target.closest('.flex.items-center.space-x-2').remove();
                        } else {
                            showToast('A poll must have at least 2 options.', 'error');
                        }
                    }
                });

                // Client-side validation for admin create post form
                adminForm.addEventListener('submit', (e) => {
                    const contentField = document.getElementById('content');
                    const postImageField = document.getElementById('post_image');
                    const pollQuestionField = adminForm.querySelector('input[name="poll_question"]');
                    const pollOptionFields = adminForm.querySelectorAll('input[name="poll_options[]"]');

                    let formValid = true;
                    // Filter out empty poll options for validation count
                    const nonEmptyPollOptions = Array.from(pollOptionFields).filter(input => input.value.trim() !== '');

                    // Check if at least one of content, image, or poll question is provided
                    const isContentEmpty = contentField.value.trim() === '';
                    const isImageProvided = postImageField.files && postImageField.files.length > 0;
                    const isPollQuestionProvided = pollQuestionField.value.trim() !== '';

                    if (isContentEmpty && !isImageProvided && !isPollQuestionProvided) {
                        showToast('Please provide content, an image, or create a poll for your post.', 'error');
                        formValid = false;
                    }

                    // Poll specific validations
                    if (isPollQuestionProvided && nonEmptyPollOptions.length < 2) {
                        showToast('A poll requires at least two options.', 'error');
                        formValid = false;
                    }

                    if (!isPollQuestionProvided && nonEmptyPollOptions.length >= 1) { // Changed to >=1 because if a user types something in an option field without a question, it's an error.
                        showToast('Poll options provided without a poll question.', 'error');
                        formValid = false;
                    }

                    if (!formValid) {
                        e.preventDefault(); // Stop form submission
                    }
                });
            }
        });
    </script>
    <button id="themeToggle" class="fixed bottom-20 right-6 w-12 h-12 bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] text-[var(--text-primary)] rounded-full shadow-lg transition-all duration-300 flex items-center justify-center z-50 group">
        <i class="fas fa-moon group-hover:scale-110 transition-transform"></i>
    </button>

    <button id="scrollTopBtn" onclick="scrollToTop()" class="fixed bottom-6 right-6 w-12 h-12 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white rounded-full shadow-lg opacity-0 translate-y-4 pointer-events-none transition-all duration-300 flex items-center justify-center z-50 group">
        <i class="fas fa-arrow-up group-hover:scale-110 transition-transform"></i>
    </button>
    <script>
        // Ensure these variables are defined for the theme toggle and scroll top
        const elements = {
            scrollTopBtn: document.getElementById('scrollTopBtn'),
            themeToggle: document.getElementById('themeToggle'),
            html: document.documentElement
        };
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                elements.scrollTopBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            } else {
                elements.scrollTopBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            }
        });
        const scrollToTop = () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };
        // Default to 'dark' if no theme is stored, or use stored theme
        let currentTheme = localStorage.getItem('theme') || 'dark';
        const applyTheme = (theme) => {
            elements.html.classList.toggle('light', theme === 'light');
            const icon = elements.themeToggle.querySelector('i');
            icon.className = theme === 'light' ? 'fas fa-sun group-hover:scale-110 transition-transform' : 'fas fa-moon group-hover:scale-110 transition-transform';
            localStorage.setItem('theme', theme);
        };
        applyTheme(currentTheme); // Apply theme on page load
        elements.themeToggle.addEventListener('click', () => {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(currentTheme);
        });
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>