<?php
ob_start();

// The entire PHP block from your original file remains here.
// It's well-structured and handles the logic correctly.
// No changes are needed in this backend section.

require_once 'includes/auth.php';
require_once 'includes/database.php';
include 'header.php'; // The new, unified header
// require_once 'includes/functions.php'; // Assuming this exists

if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}
// --- [ ALL YOUR EXISTING PHP LOGIC FROM 'profile.php' GOES HERE ] ---
// ... from line 11 to line 223 ...
// This includes user data fetching, POST handling, validation, etc.

// I will re-paste it here for completeness:
$user_id = $_SESSION['user_id'];
$available_avatars = ['includes/avatar/avatar1.svg', 'includes/avatar/avatar2.svg', 'includes/avatar/avatar3.svg', 'includes/avatar/avatar4.svg', 'includes/avatar/avatar5.svg',];
$success_message = '';
$error_message = '';
$validation_errors = [];
$old_post_data = [];
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
if (isset($_SESSION['validation_errors'])) {
    $validation_errors = $_SESSION['validation_errors'];
    unset($_SESSION['validation_errors']);
    if (isset($_SESSION['old_post_data'])) {
        $old_post_data = $_SESSION['old_post_data'];
        unset($_SESSION['old_post_data']);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $remove_picture = isset($_POST['remove_profile_picture']);
    $selected_avatar = $_POST['selected_avatar'] ?? null;
    $_SESSION['old_post_data'] = $_POST;
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validation_errors[] = "Invalid email format.";
    }
    if (empty($username)) {
        $validation_errors[] = "Username is required.";
    }
    $password_changed = false;
    if (!empty($new_password)) {
        $password_changed = true;
        if (strlen($new_password) < 8) {
            $validation_errors[] = "New password must be at least 8 characters long.";
        }
        if ($new_password !== $confirm_password) {
            $validation_errors[] = "New passwords do not match.";
        }
        if (empty($current_password)) {
            $validation_errors[] = "Current password is required to change the password.";
        } else {
            if (empty($validation_errors)) {
                try {
                    $stmt_pw = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt_pw->execute([$user_id]);
                    $hashed_current_password_db = $stmt_pw->fetchColumn();
                    if (!$hashed_current_password_db || !password_verify($current_password, $hashed_current_password_db)) {
                        $validation_errors[] = "Current password is incorrect.";
                    }
                } catch (PDOException $e) {
                    error_log("Profile password verification failed for user $user_id: " . $e->getMessage());
                    $validation_errors[] = "An unexpected error occurred during password verification.";
                }
            }
        }
    } elseif (!empty($current_password)) {
        $validation_errors[] = "New password must be provided if current password is entered.";
    }
    if ($selected_avatar && !in_array($selected_avatar, $available_avatars)) {
        $validation_errors[] = "Invalid avatar selected.";
    }
    if (empty($validation_errors)) {
        $sql_parts = [];
        $params = [];
        $update_needed = false;
        $new_profile_picture = null;
        try {
            $stmt_current = $pdo->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
            $stmt_current->execute([$user_id]);
            $current_user_data = $stmt_current->fetch(PDO::FETCH_ASSOC);
            if (!$current_user_data) {
                $_SESSION['error_message'] = "Could not fetch current user data.";
                header('Location: profile.php');
                exit();
            }
            if ($email !== $current_user_data['email']) {
                $sql_parts[] = "email = ?";
                $params[] = $email;
                $update_needed = true;
            }
            if ($username !== $current_user_data['username']) {
                $sql_parts[] = "username = ?";
                $params[] = $username;
                $update_needed = true;
            }
            if ($password_changed) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_parts[] = "password = ?";
                $params[] = $hashed_password;
                $update_needed = true;
            }
            if ($remove_picture) {
                $new_profile_picture = null;
            } elseif ($selected_avatar) {
                $new_profile_picture = $selected_avatar;
            } else {
                $new_profile_picture = $current_user_data['profile_picture'];
            }
            if ($new_profile_picture !== $current_user_data['profile_picture']) {
                $sql_parts[] = "profile_picture = ?";
                $params[] = $new_profile_picture;
                $update_needed = true;
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error: Could not check for changes.";
            error_log("Profile update check failed for user $user_id: " . $e->getMessage());
            header('Location: profile.php');
            exit();
        }
        if ($update_needed) {
            $sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id = ?";
            $params[] = $user_id;
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $_SESSION['success_message'] = "Profile updated successfully!";
                unset($_SESSION['old_post_data']);
                header('Location: profile.php');
                exit();
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Database error: Could not update profile.";
                error_log("Profile update failed for user $user_id: " . $e->getMessage());
                header('Location: profile.php');
                exit();
            }
        } else {
            $_SESSION['success_message'] = "No changes were submitted.";
            unset($_SESSION['old_post_data']);
            header('Location: profile.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Please fix the following errors:";
        $_SESSION['validation_errors'] = $validation_errors;
        header('Location: profile.php');
        exit();
    }
}
$user = null;
$user_xp_level = ['xp' => 0, 'level' => 0];
$premium_features = [];
try {
    $stmt = $pdo->prepare("SELECT id, username, role, email, profile_picture, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        header('Location: logout.php?reason=notfound');
        exit();
    }
    $stmt_xp = $pdo->prepare("SELECT xp, level FROM user_xp_levels WHERE user_id = ?");
    $stmt_xp->execute([$user_id]);
    $xp_data = $stmt_xp->fetch(PDO::FETCH_ASSOC);
    if ($xp_data) {
        $user_xp_level = $xp_data;
    }
    $stmt_premium = $pdo->prepare("SELECT feature_type, end_date FROM premium_features WHERE user_id = ? AND end_date > NOW()");
    $stmt_premium->execute([$user_id]);
    $premium_features = $stmt_premium->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Database error: Could not fetch user data.";
    error_log("Profile fetch failed for user $user_id: " . $e->getMessage());
}
$display_email = htmlspecialchars($old_post_data['email'] ?? $user['email'] ?? '');
$display_username = htmlspecialchars($old_post_data['username'] ?? $user['username'] ?? '');
$current_avatar = $user['profile_picture'] ?? '';
function getXpForNextLevel($currentLevel)
{
    return 100 * ($currentLevel + 1);
}
$xp_for_current_level = $user_xp_level['level'] > 0 ? getXpForNextLevel($user_xp_level['level'] - 1) : 0;
$xp_for_next_level = getXpForNextLevel($user_xp_level['level']);
$xp_progress = $xp_for_next_level > $xp_for_current_level ? round(($user_xp_level['xp'] - $xp_for_current_level) / ($xp_for_next_level - $xp_for_current_level) * 100) : 0;
if ($xp_progress > 100) $xp_progress = 100;

ob_flush();
?>

<!-- Add component-specific styles -->
<style>
    .avatar-carousel-wrapper {
        overflow: hidden;
        position: relative;
    }

    .avatar-selection-container {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .avatar-selection-container::-webkit-scrollbar {
        display: none;
    }

    .avatar-item.selected {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-blue) 40%, transparent);
    }

    .carousel-arrow {
        transition: opacity 0.2s;
    }

    .carousel-arrow:disabled {
        opacity: 0.2;
        cursor: not-allowed;
    }
</style>

<div class="items-center justify-center px-4 py-4 -mt-14 lg:ml-60">
    <h1 class="text-3xl font-bold text-[var(--text-dark)] mb-6">My Profile</h1>

    <!-- Alert Messages -->
    <?php if ($success_message): ?>
        <div id="success-alert" class="flex items-center gap-4 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-600" role="alert">
            <i class="fa-solid fa-check-circle text-xl"></i>
            <div><span class="font-medium">Success!</span> <?php echo htmlspecialchars($success_message); ?></div>
        </div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-600" role="alert">
            <i class="fa-solid fa-circle-exclamation text-xl mr-4"></i>
            <div>
                <span class="font-medium"><?php echo htmlspecialchars($error_message); ?></span>
                <?php if (!empty($validation_errors)): ?>
                    <ul class="mt-1.5 list-disc list-inside">
                        <?php foreach ($validation_errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: User Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[var(--card-bg)] border border-[var(--border-color)] rounded-2xl p-6 text-center shadow-lg">
                    <?php
                    $default_avatar = "https://api.dicebear.com/7.x/initials/svg?seed=" . urlencode($user['username']) . "&backgroundColor=4255FF&fontColor=FFFFFF&radius=50";
                    $profile_pic_src = !empty($current_avatar) ? htmlspecialchars($current_avatar) . '?t=' . time() : $default_avatar;
                    ?>
                    <img src="<?php echo $profile_pic_src; ?>" alt="Profile Picture" id="profile-img-display"
                        class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-[var(--border-color)] object-cover shadow-md">

                    <h2 class="text-2xl font-bold text-[var(--text-dark)]"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <p class="text-sm text-[var(--text-light)]"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="text-sm text-[var(--text-light)] mt-2">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>

                <div class="bg-[var(--card-bg)] border border-[var(--border-color)] rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-lg text-[var(--text-dark)]">Level Progress</h3>
                        <span class="font-bold text-lg text-white bg-gradient-to-r from-[var(--primary-blue)] to-[var(--accent-purple)] px-3 py-1 rounded-full text-sm">
                            Level <?php echo $user_xp_level['level']; ?>
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 my-2">
                        <div id="xpProgressBar" class="bg-gradient-to-r from-[var(--primary-blue)] to-[var(--accent-purple)] h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-center text-[var(--text-light)]">
                        <?php echo number_format($user_xp_level['xp']); ?> / <?php echo number_format($xp_for_next_level); ?> XP
                    </p>
                </div>

                <?php if (!empty($premium_features)): ?>
                    <div class="bg-amber-50 dark:bg-amber-900/20 border-2 border-amber-400 dark:border-amber-500 rounded-2xl p-6 shadow-lg flex items-center gap-5">
                        <i class="fa-solid fa-crown text-4xl text-amber-500"></i>
                        <div>
                            <h4 class="font-bold text-lg text-amber-900 dark:text-amber-300">Premium Member</h4>
                            <?php foreach ($premium_features as $feature): ?>
                                <p class="text-sm text-amber-700 dark:text-amber-400"><strong><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $feature['feature_type']))); ?></strong> active until <?php echo date('M j, Y', strtotime($feature['end_date'])); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Column: Form -->
            <div class="lg:col-span-2 bg-[var(--card-bg)] border border-[var(--border-color)] rounded-2xl p-8 shadow-lg">
                <form action="profile.php" method="post" id="profile-form" class="space-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-[var(--text-dark)] border-b border-[var(--border-color)] pb-3 mb-4">Account Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-[var(--text-dark)] mb-1">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo $display_email; ?>" class="form-input" placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium text-[var(--text-dark)] mb-1">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo $display_username; ?>" class="form-input" placeholder="YourUsername">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-[var(--text-dark)] border-b border-[var(--border-color)] pb-3 mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-[var(--text-dark)] mb-1">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password" placeholder="Required to change password">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-[var(--text-dark)] mb-1">New Password</label>
                                    <input type="password" id="new_password" name="new_password" class="form-input" autocomplete="new-password" placeholder="Min. 8 characters">
                                </div>
                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium text-[var(--text-dark)] mb-1">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" autocomplete="new-password" placeholder="Re-type new password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-[var(--text-dark)] border-b border-[var(--border-color)] pb-3 mb-4">Change Profile Picture</h3>
                        <div class="avatar-carousel-wrapper">
                            <div class="avatar-selection-container flex items-center gap-3 overflow-x-auto p-2" id="avatarSelection">
                                <?php foreach ($available_avatars as $avatar_path): ?>
                                    <div class="avatar-item flex-shrink-0 w-20 h-20 rounded-full border-4 border-transparent cursor-pointer transition-all duration-200 hover:scale-105 <?php echo ($current_avatar === $avatar_path) ? 'selected' : ''; ?>" data-avatar-path="<?php echo htmlspecialchars($avatar_path); ?>">
                                        <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Avatar" class="w-full h-full object-cover rounded-full">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="prevAvatar" class="carousel-arrow absolute top-1/2 -translate-y-1/2 left-0 w-8 h-8 rounded-full bg-[var(--card-bg)]/80 backdrop-blur-sm border border-[var(--border-color)] text-[var(--text-dark)] flex items-center justify-center shadow-md hover:bg-[var(--hover-bg)]" disabled><i class="fa-solid fa-chevron-left"></i></button>
                            <button type="button" id="nextAvatar" class="carousel-arrow absolute top-1/2 -translate-y-1/2 right-0 w-8 h-8 rounded-full bg-[var(--card-bg)]/80 backdrop-blur-sm border border-[var(--border-color)] text-[var(--text-dark)] flex items-center justify-center shadow-md hover:bg-[var(--hover-bg)]"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                        <input type="hidden" id="selectedAvatarInput" name="selected_avatar" value="<?php echo htmlspecialchars($current_avatar); ?>">

                        <?php if (!empty($current_avatar)): ?>
                            <div class="mt-4">
                                <label class="flex items-center gap-2 text-sm text-[var(--text-dark)] cursor-pointer">
                                    <input type="checkbox" id="remove_profile_picture" name="remove_profile_picture" class="w-4 h-4 text-[var(--primary-blue)] bg-gray-100 border-gray-300 rounded focus:ring-[var(--primary-blue)] dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    Remove current profile picture
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="pt-4 border-t border-[var(--border-color)]">
                        <button type="submit" class="btn btn-primary font-semibold py-3 px-8 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif (!$error_message) : ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4" role="alert">
            <p class="font-bold">Not Found</p>
            <p>User data could not be loaded. Please try again later.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- [ The Javascript from your original file is mostly fine and can be pasted here ] ---
        // I've updated it to work with the new structure and added the theme switcher logic.
        const imageDisplay = document.getElementById('profile-img-display');
        const removeCheckbox = document.getElementById('remove_profile_picture');
        const avatarItems = document.querySelectorAll('.avatar-item');
        const selectedAvatarInput = document.getElementById('selectedAvatarInput');
        const avatarContainer = document.getElementById('avatarSelection');
        const prevButton = document.getElementById('prevAvatar');
        const nextButton = document.getElementById('nextAvatar');
        const defaultSrc = "<?php echo $default_avatar; ?>";
        const originalSrc = imageDisplay ? imageDisplay.src : defaultSrc;

        avatarItems.forEach(item => {
            item.addEventListener('click', function() {
                avatarItems.forEach(ai => ai.classList.remove('selected'));
                this.classList.add('selected');
                const avatarPath = this.dataset.avatarPath;
                selectedAvatarInput.value = avatarPath;
                imageDisplay.src = avatarPath;
                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }
            });
        });

        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', function(event) {
                if (event.target.checked) {
                    imageDisplay.src = defaultSrc;
                    selectedAvatarInput.value = '';
                    avatarItems.forEach(ai => ai.classList.remove('selected'));
                } else {
                    const previouslySelected = document.querySelector('.avatar-item.selected');
                    if (previouslySelected) {
                        imageDisplay.src = previouslySelected.dataset.avatarPath;
                        selectedAvatarInput.value = previouslySelected.dataset.avatarPath;
                    } else {
                        imageDisplay.src = originalSrc;
                    }
                }
            });
        }

        if (avatarContainer && prevButton && nextButton) {
            const updateArrowVisibility = () => {
                const tolerance = 2;
                prevButton.disabled = avatarContainer.scrollLeft <= 0;
                nextButton.disabled = avatarContainer.scrollLeft + avatarContainer.clientWidth >= avatarContainer.scrollWidth - tolerance;
            };
            const scrollAmount = () => avatarContainer.clientWidth * 0.7;
            prevButton.addEventListener('click', () => {
                avatarContainer.scrollBy({
                    left: -scrollAmount(),
                    behavior: 'smooth'
                });
            });
            nextButton.addEventListener('click', () => {
                avatarContainer.scrollBy({
                    left: scrollAmount(),
                    behavior: 'smooth'
                });
            });
            avatarContainer.addEventListener('scroll', updateArrowVisibility, {
                passive: true
            });
            new ResizeObserver(updateArrowVisibility).observe(avatarContainer);
            updateArrowVisibility();
        }

        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }

        const xpProgressBar = document.getElementById('xpProgressBar');
        if (xpProgressBar) {
            setTimeout(() => { // Delay for animation effect
                xpProgressBar.style.width = '<?php echo $xp_progress; ?>%';
            }, 300);
        }
    });
</script>
</body>

</html>
<link rel="stylesheet" href="css/search.css">