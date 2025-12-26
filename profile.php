<?php
ob_start();
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'includes/PHPMailer/src/Exception.php';
require_once 'includes/PHPMailer/src/PHPMailer.php';
require_once 'includes/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isLoggedIn()) {
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: login.php?redirect=$redirect_url");
    exit();
}
$message = '';
$message_type = '';
$user_id = $_SESSION['user_id'];
$user = null;
$user_xp_level = ['xp' => 0, 'level' => 1];
$available_avatars = [
    'includes/avatar/avatar1.svg',
    'includes/avatar/avatar2.svg',
    'includes/avatar/avatar3.svg',
    'includes/avatar/avatar4.svg',
    'includes/avatar/avatar5.svg',
    'includes/avatar/avatar6.png',
    'includes/avatar/avatar7.png',
    'includes/avatar/avatar8.png',
    'includes/avatar/avatar9.png',
    'includes/avatar/avatar10.png',
    'includes/avatar/avatar11.png',
    'includes/avatar/avatar12.png',
    'includes/avatar/avatar13.png',
    'includes/avatar/avatar14.png',
    'includes/avatar/avatar15.png',
    'includes/avatar/avatar16.png',
    'includes/avatar/avatar17.png',
    'includes/avatar/avatar18.png',
    'includes/avatar/avatar19.png',
    'includes/avatar/avatar20.png',
    'includes/avatar/avatar21.png',
    'includes/avatar/avatar22.png',
    'includes/avatar/avatar23.png',
    'includes/avatar/avatar24.png',
    'includes/avatar/avatar25.png',
    'includes/avatar/avatar26.png',
    'includes/avatar/avatar27.png',
    'includes/avatar/avatar28.png',
    'includes/avatar/avatar29.png',
    'includes/avatar/avatar30.png',
    'includes/avatar/avatar31.png',
    'includes/avatar/avatar32.png',
    'includes/avatar/avatar33.png',
    'includes/avatar/avatar34.png',
    'includes/avatar/avatar35.png',
    'includes/avatar/avatar36.png',
    'includes/avatar/avatar37.png',
    'includes/avatar/avatar38.png',
    'includes/avatar/avatar39.png',
    'includes/avatar/avatar40.png',
    'includes/avatar/avatar41.png',
    'includes/avatar/avatar42.png',
    'includes/avatar/avatar43.png',
    'includes/avatar/avatar44.png',
    'includes/avatar/avatar46.png',
    'includes/avatar/avatar47.png',
    'includes/avatar/avatar48.png',
    'includes/avatar/avatar49.png',
    'includes/avatar/avatar50.png',
    'includes/avatar/avatar51.png',
    'includes/avatar/avatar52.png',
    'includes/avatar/avatar53.png',
    'includes/avatar/avatar54.png',
    'includes/avatar/avatar55.png',
    'includes/avatar/avatar56.png',
    'includes/avatar/avatar57.png',
    'includes/avatar/avatar58.png',
    'includes/avatar/avatar59.png',
    'includes/avatar/avatar60.png',
    'includes/avatar/avatar61.png',
    'includes/avatar/avatar62.png',
    'includes/avatar/avatar63.png',
    'includes/avatar/avatar64.png',
    'includes/avatar/avatar65.png',
    'includes/avatar/avatar66.png',
    'includes/avatar/avatar67.png',
    'includes/avatar/avatar68.png',
    'includes/avatar/avatar69.png',
    'includes/avatar/avatar70.png',
    'includes/avatar/avatar71.png',
    'includes/avatar/avatar72.png',
    'includes/avatar/avatar73.png',
    'includes/avatar/avatar74.png',
    'includes/avatar/avatar75.png',
    'includes/avatar/avatar76.png',
    'includes/avatar/avatar77.png',
    'includes/avatar/avatar78.png',
    'includes/avatar/avatar79.png',
    'includes/avatar/avatar80.png',
];
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
} catch (PDOException $e) {
    $message = "Failed to load profile data.";
    $message_type = "error";
    error_log("Profile Load PDO Error: " . $e->getMessage());
}
$current_username = $user['username'] ?? '';
$current_email = $user['email'] ?? '';
$current_selected_avatar = $user['profile_picture'] ?? null;
$default_avatar = "https://api.dicebear.com/7.x/initials/svg?seed=" . urlencode($user['username']) . "&backgroundColor=4255FF&fontColor=FFFFFF&radius=50";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_username = sanitize($_POST['username']);
        $new_selected_avatar = sanitize($_POST['selected_avatar'] ?? null);
        $remove_picture = isset($_POST['remove_profile_picture']);
        if (empty($new_username)) {
            $message = "Username cannot be empty.";
            $message_type = "error";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $stmt->execute([$new_username, $user_id]);
                if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                    $message = "Username already taken by another user.";
                    $message_type = "error";
                } else {
                    $final_profile_picture = $current_selected_avatar;
                    if ($remove_picture) {
                        $final_profile_picture = null;
                    } elseif (!empty($new_selected_avatar) && in_array($new_selected_avatar, $available_avatars)) {
                        $final_profile_picture = $new_selected_avatar;
                    }
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, profile_picture = ? WHERE id = ?");
                    if ($stmt->execute([$new_username, $final_profile_picture, $user_id])) {
                        $message = "Profile updated successfully!";
                        $message_type = "success";
                        $_SESSION['username'] = $new_username;
                        $user['username'] = $new_username;
                        $user['profile_picture'] = $final_profile_picture;
                        $current_selected_avatar = $final_profile_picture;
                    } else {
                        $message = "Error updating profile. Please try again.";
                        $message_type = "error";
                    }
                }
            } catch (PDOException $e) {
                $message = "A database error occurred. Please try again.";
                $message_type = "error";
                error_log("Profile Update PDO Exception: " . $e->getMessage());
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_password_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user_password_data && password_verify($current_password, $user_password_data['password'])) {
            if ($new_password !== $confirm_new_password) {
                $message = "New passwords do not match.";
                $message_type = "error";
            } elseif (strlen($new_password) < 8 || !preg_match("/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/", $new_password)) {
                $message = "Password must be at least 8 characters and include letters and numbers.";
                $message_type = "error";
            } else {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                if ($stmt->execute([$hashed_new_password, $user_id])) {
                    $message = "Password changed successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error changing password.";
                    $message_type = "error";
                }
            }
        } else {
            $message = "Incorrect current password.";
            $message_type = "error";
        }
    } elseif (isset($_POST['send_email_otp'])) {
        $email_to_send_otp = trim(strtolower(sanitize($_POST['new_email'])));
        if (!filter_var($email_to_send_otp, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
            $message_type = "error";
        } elseif ($email_to_send_otp === $current_email) {
            $message = "This is already your current email address.";
            $message_type = "error";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email_to_send_otp, $user_id]);
                if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                    $message = "This email address is already in use.";
                    $message_type = "error";
                } else {
                    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $otp_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                    $_SESSION['email_update_otp'] = $otp;
                    $_SESSION['email_update_email'] = $email_to_send_otp;
                    $_SESSION['email_update_otp_expires_at'] = $otp_expires_at;

                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'quizzletmaster.in@gmail.com';
                    $mail->Password = 'woza wlom zcnl zswx';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom('quizzletmaster.in@gmail.com', 'QuizzletMaster');
                    $mail->addAddress($email_to_send_otp);
                    $mail->isHTML(true);

                    // Reusing the beautiful HTML template structure
                    $safe_username = htmlspecialchars($user['username'] ?? 'User', ENT_QUOTES);
                    $year = date("Y");
                    $emailBody = <<<HTML
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Your QuizzletMaster Email Update OTP</title>
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f5f9;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        a {
            text-decoration: none;
        }
        .container {
            padding: 20px 0;
            text-align: center;
        }
        .full-width {
            width: 100% !important;
            max-width: 100% !important;
        }
        @media screen and (max-width: 600px) {
            .full-width-mobile {
                width: 100% !important;
                max-width: 100% !important;
            }
            .content-padding-mobile {
                padding: 20px !important;
            }
            .heading-mobile {
                font-size: 20px !important;
            }
            .paragraph-mobile {
                font-size: 15px !important;
            }
            .otp-code-mobile {
                font-size: 30px !important;
                letter-spacing: 5px !important;
            }
        }
    </style>
</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f5f9;">
    <center style="width: 100%; background-color: #f1f5f9;">
        <div style="max-width: 700px; margin: 0 auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border-radius: 12px;" class="full-width full-width-mobile">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background: #ffffff; border-radius: 12px;">
                <tr>
                    <td style="padding: 40px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #1e293b;" class="content-padding-mobile">
                        <h2 style="margin: 0 0 20px; font-size: 24px; font-weight: bold; color: #0f172a;" class="heading-mobile">Hello, {$safe_username}!</h2>
                        <p style="margin: 0 0 20px;" class="paragraph-mobile">You've requested to update your email address on <strong>QuizzletMaster</strong>. To complete this, please use the One-Time Password (OTP) below to verify your new email address.</p>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td align="center" style="padding: 20px 0;">
                                    <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center;">
                                        <p style="margin: 0; font-size: 14px; color: #64748b; letter-spacing: 1px; text-transform: uppercase;" class="paragraph-mobile">Your Verification Code</p>
                                        <p style="margin: 10px 0 0; font-size: 36px; font-weight: bold; color: #6a0dad; letter-spacing: 10px; line-height: 1;" class="otp-code-mobile">
                                            {$otp}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <p style="margin: 20px 0;" class="paragraph-mobile">This OTP is valid for the next <strong>10 minutes</strong>. For your security, please do not share this code with anyone.</p>
                        <p style="margin: 0;" class="paragraph-mobile">If you did not request this email update, please ignore this message.</p>
                    </td>
                </tr>
            </table>
        </div>
        <div style="max-width: 700px; margin: 0 auto;" class="container full-width full-width-mobile">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td style="padding: 30px 10px; width: 100%; font-size: 12px; font-family: sans-serif; line-height: 18px; text-align: center; color: #64748b;" class="paragraph-mobile">
                        <p style="margin: 0 0 10px;">Need help? Contact our support <a href="mailto:quizzletmaster.in@gmail.com" style="color: #6a0dad; text-decoration: underline;">quizzletmaster.in@gmail.com</a></p>
                        <p style="margin: 0 0 10px;">Thank You</p>
                        <p style="margin: 0;">© 2024 - {$year} QuizzletMaster</p>
                        <p style="margin: 0;">Developed in India</p>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
HTML;
                    $altBody = "Dear " . ($user['username'] ?? 'User') . ",\n\n"
                        . "You've requested to update your email address on QuizzletMaster.\n"
                        . "Your OTP for email update is: {$otp}\n"
                        . "This code is valid for 10 minutes.\n\n"
                        . "If you didn’t request this, just ignore this message.\n\n"
                        . "Regards,\nThe QuizzletMaster Team";

                    $mail->Subject = 'Your QuizzletMaster Email Update OTP';
                    $mail->Body = $emailBody;
                    $mail->AltBody = $altBody;

                    $mail->send();
                    $message = 'OTP sent to your new email. It will expire in 10 minutes.';
                    $message_type = "success";
                    $_SESSION['show_email_otp_field'] = true;
                }
            } catch (Exception $e) {
                $message = "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
                $message_type = "error";
                error_log("PHPMailer Error (Email Update): " . $e->getMessage());
            }
        }
    } elseif (isset($_POST['verify_and_update_email'])) {
        $otp_input = trim(sanitize($_POST['email_otp']));
        $new_email_to_verify = $_SESSION['email_update_email'] ?? '';
        if (!isset($_SESSION['email_update_otp']) || empty($new_email_to_verify)) {
            $message = "Session expired. Please request an OTP again.";
            $message_type = "error";
        } elseif (empty($otp_input)) {
            $message = "Please enter the OTP.";
            $message_type = "error";
        } elseif ($_SESSION['email_update_otp'] !== $otp_input) {
            $message = "Invalid OTP.";
            $message_type = "error";
        } elseif (strtotime($_SESSION['email_update_otp_expires_at']) < time()) {
            $message = "OTP has expired. Please request a new one.";
            $message_type = "error";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            if ($stmt->execute([$new_email_to_verify, $user_id])) {
                $message = "Email address updated successfully!";
                $message_type = "success";
                unset($_SESSION['email_update_otp'], $_SESSION['email_update_email'], $_SESSION['email_update_otp_expires_at'], $_SESSION['show_email_otp_field']);
                $user['email'] = $new_email_to_verify;
            } else {
                $message = "Error updating email. Please try again.";
                $message_type = "error";
            }
        }
    }
}
$show_email_otp_field = isset($_SESSION['show_email_otp_field']) && $_SESSION['show_email_otp_field'];
function getXpForNextLevel($currentLevel)
{
    return 50 * $currentLevel * ($currentLevel + 1);
}
$xp_for_current_level_start = $user_xp_level['level'] > 1 ? getXpForNextLevel($user_xp_level['level'] - 1) : 0;
$xp_for_next_level_end = getXpForNextLevel($user_xp_level['level']);
$xp_in_current_level = $user_xp_level['xp'] - $xp_for_current_level_start;
$xp_needed_for_level = $xp_for_next_level_end - $xp_for_current_level_start;
$xp_progress = $xp_needed_for_level > 0 ? round(($xp_in_current_level / $xp_needed_for_level) * 100) : 0;
if ($xp_progress > 100) $xp_progress = 100;
include 'header.php';
?>
<link rel="stylesheet" href="css/search.css">
<style>
    :root {
        --text-dark: #333;
        --text-light: #666;
        --bg-color: #f8f9fa;
        --card-bg: #ffffff;
        --border-color: #e0e0e0;
        --primary-blue: #007bff;
        --accent-purple: #6f42c1;
        --input-bg: #ffffff;
        --input-border: #ced4da;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-dark);
    }

    .form-input-v2 {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--input-border);
        background-color: var(--input-bg);
        color: var(--text-dark);
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .form-input-v2:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-input-v2::placeholder {
        color: var(--text-light);
    }

    .bg-white {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .avatar-selection-container {
        -ms-overflow-style: none;
        scrollbar-width: none;
        overflow-x: auto;
    }

    .avatar-selection-container::-webkit-scrollbar {
        display: none;
    }

    .avatar-item {
        border: 4px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .avatar-item.selected {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary-blue) 40%, transparent), 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: scale(1.08);
    }

    .carousel-arrow {
        flex-shrink: 0;
        width: 2.8rem;
        height: 2.8rem;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--border-color);
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .carousel-arrow:hover:not(:disabled) {
        background-color: var(--primary-blue);
        color: white;
        transform: scale(1.05);
    }

    .carousel-arrow:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: var(--primary-blue);
        color: white;
        border: 1px solid var(--primary-blue);
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    @media (max-width: 1023px) {
        .lg\:ml-60 {
            margin-left: 0;
        }
    }
</style>
<main class="px-0 py-4 -mt-14 lg:ml-60">
    <div id="toastContainer" class="toast-container"></div>
    <div class="items-center justify-center px-4 py-4">
        <h1 class="text-3xl font-bold text-[var(--text-dark)] mb-6">Account Settings</h1>
        <?php if ($user): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 text-center shadow-lg h-full flex flex-col">
                        <?php $profile_pic_src = !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) . '?t=' . time() : $default_avatar; ?>
                        <div class="relative w-32 h-32 mx-auto">
                            <img src="<?php echo $profile_pic_src; ?>" alt="Profile Picture" id="profile-img-display"
                                class="w-32 h-32 rounded-full mx-auto object-cover shadow-md ring-4 ring-offset-4 ring-offset-[var(--card-bg)] ring-[var(--primary-blue)]">
                            <span class="absolute bottom-1 right-1 flex items-center justify-center h-10 w-10 bg-gradient-to-r from-[var(--primary-blue)] to-[var(--accent-purple)] text-white font-bold text-sm rounded-full border-4 border-[var(--card-bg)]">
                                <?php echo $user_xp_level['level']; ?>
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mt-5"><?php echo htmlspecialchars($user['username']); ?></h2>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                        <div class="w-full my-6">
                            <p class="text-xs text-right font-medium text-gray-500 mb-1">
                                <?php echo number_format($xp_in_current_level); ?> / <?php echo number_format($xp_needed_for_level); ?> XP
                            </p>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div id="xpProgressBar" class="bg-gradient-to-r from-[var(--primary-blue)] to-[var(--accent-purple)] h-2 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-left text-sm space-y-3 mt-auto">
                            <div class="flex items-center gap-3 text-gray-700">
                                <i class="fa-solid fa-shield-halved w-4 text-center text-gray-400"></i>
                                <span>Role: <span class="font-semibold"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></span></span>
                            </div>
                            <div class="flex items-center gap-3 text-gray-700">
                                <i class="fa-solid fa-calendar-alt w-4 text-center text-gray-400"></i>
                                <span>Joined: <span class="font-semibold"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-lg space-y-10">
                    <form action="" method="post" id="profile-form" class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-solid fa-user-circle text-xl text-[var(--primary-blue)]"></i>
                                <h3 class="text-xl font-bold text-gray-800">Account Information</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-600 mb-1.5">Username</label>
                                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-input-v2" placeholder="YourUsername">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-solid fa-image text-xl text-[var(--primary-blue)]"></i>
                                <h3 class="text-xl font-bold text-gray-800">Profile Picture</h3>
                            </div>
                            <div class="relative flex items-center gap-4">
                                <button type="button" id="prevAvatar" aria-label="Previous avatar" class="carousel-arrow"><i class="fa-solid fa-chevron-left"></i></button>
                                <div class="flex-grow overflow-hidden">
                                    <div class="avatar-selection-container flex items-center gap-4 p-2" id="avatarSelection">
                                        <?php foreach ($available_avatars as $avatar_path): ?>
                                            <div class="avatar-item flex-shrink-0 w-20 h-20 rounded-full <?php echo ($current_selected_avatar === $avatar_path) ? 'selected' : ''; ?>" data-avatar-path="<?php echo htmlspecialchars($avatar_path); ?>" role="radio" aria-checked="<?php echo ($current_selected_avatar === $avatar_path) ? 'true' : 'false'; ?>" tabindex="0">
                                                <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Avatar" class="w-full h-full object-cover rounded-full">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button type="button" id="nextAvatar" aria-label="Next avatar" class="carousel-arrow"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                            <input type="hidden" id="selectedAvatarInput" name="selected_avatar" value="<?php echo htmlspecialchars($current_selected_avatar); ?>">
                            <?php if (!empty($current_selected_avatar)): ?>
                                <div class="mt-4 md:pl-16">
                                    <label class="inline-flex items-center gap-2.5 text-sm text-gray-500 cursor-pointer hover:text-gray-800 transition">
                                        <input type="checkbox" id="remove_profile_picture" name="remove_profile_picture" class="w-4 h-4 text-[var(--primary-blue)] bg-gray-200 border-gray-400 rounded focus:ring-offset-0 focus:ring-2 focus:ring-[var(--primary-blue)]">
                                        Revert to default initial avatar
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" name="update_profile" class="btn btn-primary font-semibold py-2.5 px-6 w-full sm:w-auto flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                <span>Save Account Info</span>
                            </button>
                        </div>
                    </form>
                    <form action="" method="post" id="changeEmailForm" class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-solid fa-envelope text-xl text-[var(--primary-blue)]"></i>
                                <h3 class="text-xl font-bold text-gray-800">Change Email</h3>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Current Email: <strong class="text-gray-700"><?php echo htmlspecialchars($user['email']); ?></strong></p>
                            <div class="space-y-4">
                                <div>
                                    <label for="new_email" class="block text-sm font-medium text-gray-600 mb-1.5">New Email Address</label>
                                    <div class="relative">
                                        <input type="email" id="new_email" name="new_email" class="form-input-v2 pr-28" placeholder="your.new@email.com" required value="<?php echo (isset($_SESSION['email_update_email'])) ? htmlspecialchars($_SESSION['email_update_email']) : ''; ?>">
                                        <button type="submit" name="send_email_otp" class="absolute inset-y-0 right-1.5 my-1.5 px-3 text-sm font-semibold rounded-md bg-indigo-50 hover:bg-indigo-100 text-indigo-600">Send OTP</button>
                                    </div>
                                </div>
                                <?php if ($show_email_otp_field): ?>
                                    <div class="animate-fade-in">
                                        <label for="email_otp" class="block text-sm font-medium text-gray-600 mb-1.5">Verification Code</label>
                                        <input type="text" id="email_otp" name="email_otp" class="form-input-v2" placeholder="Enter OTP from your email" required>
                                    </div>
                                    <div class="pt-4 border-t border-gray-200">
                                        <button type="submit" name="verify_and_update_email" class="btn bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 w-full sm:w-auto flex items-center justify-center gap-2">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Verify & Update Email</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                    <form action="" method="post" id="changePasswordForm" class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <i class="fa-solid fa-key text-xl text-[var(--primary-blue)]"></i>
                                <h3 class="text-xl font-bold text-gray-800">Change Password</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-600 mb-1.5">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" class="form-input-v2" autocomplete="current-password" placeholder="Required to change password">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-600 mb-1.5">New Password</label>
                                        <input type="password" id="new_password" name="new_password" class="form-input-v2" autocomplete="new-password" placeholder="Min. 8 characters">
                                    </div>
                                    <div>
                                        <label for="confirm_new_password" class="block text-sm font-medium text-gray-600 mb-1.5">Confirm New Password</label>
                                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-input-v2" autocomplete="new-password" placeholder="Re-type new password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" name="change_password" class="btn btn-primary font-semibold py-2.5 px-6 w-full sm:w-auto flex items-center justify-center gap-2">
                                <i class="fas fa-key"></i>
                                <span>Change Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Could not load user profile. Please try again later.</p>
        <?php endif; ?>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showToast(message, type = 'error') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-3 rounded-lg shadow-lg mb-4`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        <?php if ($message): ?>
            showToast("<?php echo addslashes(htmlspecialchars($message)); ?>", "<?php echo $message_type; ?>");
        <?php endif; ?>
        const imageDisplay = document.getElementById('profile-img-display');
        const removeCheckbox = document.getElementById('remove_profile_picture');
        const avatarItems = document.querySelectorAll('.avatar-item');
        const selectedAvatarInput = document.getElementById('selectedAvatarInput');
        const defaultSrc = "<?php echo $default_avatar; ?>";
        const originalAvatarPath = '<?php echo htmlspecialchars($current_selected_avatar); ?>';

        function selectAvatar(itemToSelect) {
            avatarItems.forEach(item => {
                item.classList.remove('selected');
                item.setAttribute('aria-checked', 'false');
            });
            if (itemToSelect) {
                itemToSelect.classList.add('selected');
                itemToSelect.setAttribute('aria-checked', 'true');
                const avatarPath = itemToSelect.dataset.avatarPath;
                selectedAvatarInput.value = avatarPath;
                if (imageDisplay) imageDisplay.src = avatarPath + '?t=' + new Date().getTime();
                if (removeCheckbox) removeCheckbox.checked = false;
            } else {
                selectedAvatarInput.value = '';
                if (imageDisplay) imageDisplay.src = defaultSrc;
            }
        }
        avatarItems.forEach(item => {
            item.addEventListener('click', () => selectAvatar(item));
            item.addEventListener('keydown', e => (e.key === 'Enter' || e.key === ' ') && (e.preventDefault(), selectAvatar(item)));
        });
        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', e => {
                if (e.target.checked) {
                    selectAvatar(null);
                } else {
                    const original = document.querySelector(`.avatar-item[data-avatar-path="${originalAvatarPath}"]`);
                    selectAvatar(original);
                }
            });
        }
        const avatarContainer = document.getElementById('avatarSelection');
        const prevButton = document.getElementById('prevAvatar');
        const nextButton = document.getElementById('nextAvatar');
        if (avatarContainer && prevButton && nextButton) {
            const updateArrowState = () => {
                prevButton.disabled = avatarContainer.scrollLeft <= 0;
                nextButton.disabled = avatarContainer.scrollLeft + avatarContainer.clientWidth >= avatarContainer.scrollWidth;
            };
            const scroll = (direction) => {
                const amount = avatarContainer.clientWidth * 0.8 * direction;
                avatarContainer.scrollBy({
                    left: amount,
                    behavior: 'smooth'
                });
            };
            prevButton.addEventListener('click', () => scroll(-1));
            nextButton.addEventListener('click', () => scroll(1));
            avatarContainer.addEventListener('scroll', updateArrowState, {
                passive: true
            });
            new ResizeObserver(updateArrowState).observe(avatarContainer);
            setTimeout(() => {
                const selected = document.querySelector('.avatar-item.selected');
                if (selected) selected.scrollIntoView({
                    behavior: 'auto',
                    block: 'nearest',
                    inline: 'center'
                });
                updateArrowState();
            }, 150);
        }
        const xpProgressBar = document.getElementById('xpProgressBar');
        if (xpProgressBar) {
            setTimeout(() => {
                xpProgressBar.style.width = '<?php echo $xp_progress; ?>%';
            }, 300);
        }
    });
</script>
<?php
ob_end_flush();
?>