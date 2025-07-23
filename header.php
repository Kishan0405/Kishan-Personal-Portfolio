<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/database.php';

function getCurrentPage(): string
{
    return basename($_SERVER['PHP_SELF']);
}

$currentPage = getCurrentPage();

$titles = [
    'index.php' => 'Home – Kishan Raj Portfolio',
    'about.php' => 'About – Kishan Raj Portfolio',
    'projects.php' => 'Projects – Kishan Raj Portfolio',
    'internships.php' => 'Internships – Kishan Raj Portfolio',
    'posts.php' => 'Posts – Kishan Raj Portfolio',
    'community.php' => 'Community – Kishan Raj Portfolio',
    'contact.php' => 'Contact – Kishan Raj Portfolio',
    'login.php' => 'Log In – Kishan Raj Portfolio',
    'register.php' => 'Register – Kishan Raj Portfolio',
    'profile.php' => 'My Profile – Kishan Raj Portfolio',
];
$descriptions = [
    'index.php' => 'Welcome to Kishan Raj\'s personal portfolio website showcasing projects, internships and all the insights as my professional journey.',
    'about.php' => 'Learn more about Kishan Raj, my background and education.',
    'projects.php' => 'Explore the projects developed by Kishan Raj.',
    'internships.php' => 'Discover Kishan Raj\'s professional experience through internships.',
    'posts.php' => 'Read Kishan Raj\'s posts and insights.',
    'community.php' => 'Join Kishan Raj\'s community which is a part of QuizzletMaster too and engage with like-minded individuals.',
    'contact.php' => 'Get in touch with Kishan Raj for any issues or opportunites.',
    'login.php' => 'Log in to your account to access all features this account is used for all QuizzletMaster Platform.',
    'register.php' => 'Create a new account to join the community. This account is used for all QuizzletMaster Platform',
];

$pageTitle = $titles[$currentPage] ?? 'Kishan Raj – Personal Portfolio';
$pageDescription = $descriptions[$currentPage] ?? 'Explore Kishan Raj\'s portfolio, projects and professional journey.';

$userLoggedIn = isLoggedIn();

$userName = '';
$userProfilePicturePath = null;

if (isLoggedIn() && isset($_SESSION['user_id'])) {
    try {
        if (isset($pdo)) {
            $stmt_user_header = $pdo->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
            $stmt_user_header->execute([$_SESSION['user_id']]);
            $userDataForHeader = $stmt_user_header->fetch(PDO::FETCH_ASSOC);

            if ($userDataForHeader) {
                $userName = htmlspecialchars($userDataForHeader['username']);
                if (!empty($userDataForHeader['profile_picture'])) {
                    $potentialPath = $userDataForHeader['profile_picture'];
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($potentialPath, '/'))) {
                        $userProfilePicturePath = htmlspecialchars(ltrim($potentialPath, '/'));
                    } elseif (file_exists($potentialPath)) {
                        $userProfilePicturePath = htmlspecialchars($potentialPath);
                    }
                }
            }
        } else {
            error_log("Header: \$pdo object not available for fetching user data.");
        }
    } catch (PDOException $e) {
        error_log("Header: Error fetching user data for profile icon: " . $e->getMessage());
    }
}

if (!$userProfilePicturePath) {
    $seed = !empty($userName) ? urlencode(preg_replace('/\s+/', '', $userName)) : 'KR';
    $userProfilePicturePath = "https://api.dicebear.com/7.x/initials/svg?seed={$seed}&backgroundColor=4255FF&fontColor=FFFFFF";
}

$sidebarItems = [
    'Home' => [
        'href' => 'index.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>',
    ],
    'About' => [
        'href' => 'about.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.876 0-5.603-.74-7.999-2.632z" />
        </svg>',
    ],
    'Projects' => [
        'href' => 'projects.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
        </svg>',
    ],
    'Internships' => [
        'href' => 'internships.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
        </svg>',
    ],
    'Posts' => [
        'href' => 'posts.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5-3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
        </svg>',
    ],
    'Community' => [
        'href' => 'community.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
        </svg>',
    ],
    'Contact' => [
        'href' => 'contact.php',
        'icon' => '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>',
    ],
];

$currentPath = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="icon" type="image/svg+xml" href="includes/kishan_raj_icon.png">
</head>

<body class="bg-[var(--bg-primary)] text-[var(--text-primary)]">
    <header class="fixed inset-x-0 top-0 h-16 flex items-center justify-between px-4 sm:px-6 z-[1000] bg-[var(--glass-bg)] backdrop-blur-lg shadow-md border-b border-[var(--glass-border)]">
        <a href="/" class="flex items-center gap-2">
            <span class="hidden sm:block text-2xl font-bold main-header-gradient">Kishan Raj</span>
        </a>
        <div class="relative">
            <button id="profileDropdownBtn" class="flex items-center gap-1.5 rounded-full p-1 pr-2 hover:bg-[var(--hover-bg)] transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary-blue)]" aria-haspopup="true" aria-expanded="false" aria-controls="profileDropdownMenu">
                <img src="<?= $userProfilePicturePath ?>" alt="User avatar" class="w-9 h-9 rounded-full object-cover border-2 border-[var(--glass-border)]">
                <span class="hidden sm:inline text-sm text-[var(--text-primary)] font-medium mr-1"><?= htmlspecialchars($userName) ?></span>
                <svg class="w-5 h-5 text-[var(--text-light)]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="profileDropdownMenu" data-state="closed" class="absolute top-full right-0 mt-2 w-56 bg-[var(--bg-secondary)] border border-[var(--border)] rounded-lg shadow-lg py-2 opacity-0 scale-95 transition-all duration-150 hidden data-[state=open]:opacity-100 data-[state=open]:scale-100 data-[state=open]:block" aria-labelledby="profileDropdownBtn">
                <div id="mobileNavContainer" class="lg:hidden"></div>
                <?php if ($userLoggedIn): ?>
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-2 text-[var(--text-primary)] hover:bg-[var(--surface-hover)] transition-colors"><i class="fas fa-user w-5 text-center"></i>My Profile</a>
                    <hr class="my-1 border-[var(--border)]">
                    <form action="logout.php" method="post"><button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"><i class="fas fa-right-from-bracket w-5 text-center"></i>Log Out</button></form>
                <?php else: ?>
                    <a href="login.php" class="flex items-center gap-3 px-4 py-2 text-[var(--text-primary)] hover:bg-[var(--surface-hover)] transition-colors"><i class="fas fa-right-to-bracket w-5 text-center"></i>Log In</a>
                    <a href="register.php" class="flex items-center gap-3 px-4 py-2 text-[var(--text-primary)] hover:bg-[var(--surface-hover)] transition-colors"><i class="fas fa-user-plus w-5 text-center"></i>Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <aside id="desktopSidebar" class="hidden lg:flex flex-col fixed top-16 left-0 h-[calc(100vh-4rem)] w-60 bg-[var(--glass-bg)] backdrop-blur-lg shadow-md border-b border-[var(--glass-border)] z-[900]">
            <nav class="flex-1 mt-5 px-5 space-y-10">
                <?php foreach ($sidebarItems as $label => $item): ?>
                    <?php $isActive = ($item['href'] === $currentPath); ?>
                    <a href="<?= $item['href'] ?>" class="flex items-center gap-3 py-3 px-3 rounded-md font-medium transition-colors <?= $isActive ? 'bg-[var(--primary-blue)]/10 text-[var(--primary-blue)]' : 'text-[var(--text-primary)] hover:bg-[var(--hover-bg)]' ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
                        <?= $item['icon'] ?>
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>

        <main class="flex-1 p-10 lg:ml-60 mt-18 overflow-y-auto"> </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');
            const desktopSidebar = document.getElementById('desktopSidebar');
            const mobileNav = document.getElementById('mobileNavContainer');

            if (profileBtn && profileMenu) {
                function toggleDropdown(forceClose = false) {
                    const open = profileMenu.dataset.state === 'open';
                    if (forceClose || open) {
                        profileMenu.dataset.state = 'closed';
                        profileBtn.setAttribute('aria-expanded', 'false');
                    } else {
                        profileMenu.dataset.state = 'open';
                        profileBtn.setAttribute('aria-expanded', 'true');
                    }
                }

                profileBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    toggleDropdown();
                });

                document.addEventListener('click', e => {
                    if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                        toggleDropdown(true);
                    }
                });

                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') {
                        toggleDropdown(true);
                    }
                });
            }

            function syncSidebarToDropdown() {
                if (!desktopSidebar || !mobileNav) return;

                const isDesktopSidebarHidden = window.getComputedStyle(desktopSidebar).display === 'none';

                if (isDesktopSidebarHidden) {
                    if (mobileNav.childElementCount === 0) {
                        const fragment = document.createDocumentFragment();
                        desktopSidebar.querySelectorAll('a').forEach(link => {
                            const clone = link.cloneNode(true);
                            clone.classList.remove('bg-[var(--primary-blue)]/10', 'text-[var(--primary-blue)]');
                            clone.classList.add('text-[var(--text-primary)]', 'hover:bg-[var(--hover-bg)]');
                            clone.classList.replace('px-3', 'px-4');
                            fragment.appendChild(clone);
                        });
                        if (fragment.childElementCount) {
                            mobileNav.appendChild(fragment);
                            const hr = document.createElement('hr');
                            hr.className = 'my-1 border-[var(--border-color)]';
                            mobileNav.appendChild(hr);
                        }
                    }
                } else {
                    mobileNav.innerHTML = '';
                }
            }

            syncSidebarToDropdown();

            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(syncSidebarToDropdown, 150);
            });
        });
    </script>
</body>

</html>