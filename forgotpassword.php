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

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$message = '';
$email = '';
$otp_sent = false;
$new_password = '';
$confirm_new_password = '';
$message_type = 'error';
if (isset($_SESSION['reset_otp_email'])) {
    $email = $_SESSION['reset_otp_email'];
    $otp_sent = true;
}
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'error';
}
if (isset($_POST['request_otp'])) {
    $email_to_send = trim(strtolower(sanitize($_POST['email'])));
    $email = $email_to_send;
    if (!filter_var($email_to_send, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address format. Please use a valid email (e.g., user@gmail.com).";
        $message_type = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email_to_send]);
            $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$existing_user) {
                $message = "No account found with this email address.";
                $message_type = 'error';
            } else {
                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $otp_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_otp_email'] = $email_to_send;
                $_SESSION['reset_otp_expires_at'] = $otp_expires_at;
                $safe_email = htmlspecialchars($email_to_send, ENT_QUOTES);
                $year = date("Y");
                $emailBody = <<<HTML
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Password Reset OTP for QuizzletMaster</title>
    <style>
        html, body { margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important; background: #f1f5f9; }
        * { -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important; }
        table { border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important; }
        a { text-decoration: none; }
        .container { padding: 20px 0; text-align: center; }
        .full-width { width: 100% !important; max-width: 100% !important; }
        @media screen and (max-width: 600px) {
            .full-width-mobile { width: 100% !important; max-width: 100% !important; }
            .content-padding-mobile { padding: 20px !important; }
            .heading-mobile { font-size: 20px !important; }
            .paragraph-mobile { font-size: 15px !important; }
            .otp-code-mobile { font-size: 30px !important; letter-spacing: 5px !important; }
        }
    </style>
</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f5f9;">
    <center style="width: 100%; background-color: #f1f5f9;">
        <div style="max-width: 700px; margin: 0 auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border-radius: 12px;" class="full-width full-width-mobile">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background: #ffffff; border-radius: 12px;">
                <tr>
                    <td style="padding: 40px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #1e293b;" class="content-padding-mobile">
                        <h2 style="margin: 0 0 20px; font-size: 24px; font-weight: bold; color: #0f172a;" class="heading-mobile">Password Reset Request</h2>
                        <p style="margin: 0 0 20px;" class="paragraph-mobile">We received a request to reset the password for your QuizzletMaster account associated with <strong>{$safe_email}</strong>.</p>
                        <p style="margin: 0 0 20px;" class="paragraph-mobile">To proceed with the password reset, please use the One-Time Password (OTP) below:</p>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td align="center" style="padding: 20px 0;">
                                    <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center;">
                                        <p style="margin: 0; font-size: 14px; color: #64748b; letter-spacing: 1px; text-transform: uppercase;" class="paragraph-mobile">Your Password Reset Code</p>
                                        <p style="margin: 10px 0 0; font-size: 36px; font-weight: bold; color: #6a0dad; letter-spacing: 10px; line-height: 1;" class="otp-code-mobile">
                                            {$otp}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <p style="margin: 20px 0;" class="paragraph-mobile">This OTP is valid for the next <strong>10 minutes</strong>. For your security, please do not share this code with anyone.</p>
                        <p style="margin: 0;" class="paragraph-mobile">If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
                    </td>
                </tr>
            </table>
        </div>
        <div style="max-width: 700px; margin: 0 auto;" class="container full-width full-width-mobile">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td style="padding: 30px 10px; width: 100%; font-size: 12px; font-family: sans-serif; line-height: 18px; text-align: center; color: #64748b;" class="paragraph-mobile">
                        <p style="margin: 0 0 10px;">Need help? Contact our support <a href="mailto:quizzletmaster@gmail.com" style="color: #6a0dad; text-decoration: underline;">quizzletmaster@gmail.com</a></p>
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
                $altBody = "Dear QuizzletMaster User,\n\n"
                    . "We received a request to reset your password.\n"
                    . "Your One-Time Password (OTP) for password reset is: {$otp}\n"
                    . "This code is valid for 10 minutes.\n\n"
                    . "If you didn't request this password reset, you can safely ignore this email.\n\n"
                    . "Regards,\nThe QuizzletMaster Team";
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'quizzletmaster@gmail.com';
                $mail->Password = 'tpoy jgzb zixb dkwp';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('quizzletmaster@gmail.com', 'QuizzletMaster');
                $mail->addAddress($email_to_send);
                $mail->isHTML(true);
                $mail->Subject = 'QuizzletMaster Password Reset OTP';
                $mail->Body = $emailBody;
                $mail->AltBody = $altBody;
                $mail->send();
                $message = 'Password reset OTP has been sent to your email. Please check your inbox and spam folder.';
                $message_type = 'success';
                $otp_sent = true;
            }
        } catch (Exception $e) {
            $message = "Failed to send OTP. Please try again later. Mailer Error: " . $e->getMessage();
            $message_type = 'error';
            error_log("PHPMailer Error: " . $e->getMessage());
        } catch (PDOException $e) {
            $message = "A database error occurred. Please try again later.";
            $message_type = 'error';
            error_log("Forgot Password Email Check PDO Error: " . $e->getMessage());
        }
    }
}
if (isset($_POST['reset_password'])) {
    $email_input = trim(strtolower(sanitize($_POST['email'])));
    $otp_input = trim(sanitize($_POST['otp'] ?? ''));
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    if (isset($_SESSION['reset_otp_email'])) {
        $email = $_SESSION['reset_otp_email'];
    }
    if ($new_password !== $confirm_new_password) {
        $message = "Passwords do not match.";
        $message_type = 'error';
    } elseif (strlen($new_password) < 8 || !preg_match("/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/", $new_password)) {
        $message = "Password must be at least 8 characters long and contain both letters and numbers.";
        $message_type = 'error';
    } elseif (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_otp_expires_at']) || !isset($_SESSION['reset_otp_email'])) {
        $message = "Please request an OTP first.";
        $message_type = 'error';
    } elseif ($_SESSION['reset_otp_email'] !== $email_input) {
        $message = "The email address does not match the one for which the OTP was sent. Please re-enter the correct email and OTP.";
        $message_type = 'error';
    } elseif (empty($otp_input)) {
        $message = "Please enter the OTP.";
        $message_type = 'error';
    } elseif ($_SESSION['reset_otp'] !== $otp_input) {
        $message = "Invalid OTP.";
        $message_type = 'error';
    } elseif (strtotime($_SESSION['reset_otp_expires_at']) < time()) {
        $message = "OTP has expired. Please request a new one.";
        $message_type = 'error';
        unset($_SESSION['reset_otp']);
        unset($_SESSION['reset_otp_email']);
        unset($_SESSION['reset_otp_expires_at']);
    }
    if (empty($message)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($stmt->execute([$hashed_password, $email_input])) {
                unset($_SESSION['reset_otp']);
                unset($_SESSION['reset_otp_email']);
                unset($_SESSION['reset_otp_expires_at']);
                $message = "Your password has been successfully reset. You can now log in.";
                $message_type = "success";
                header("Location: login.php?message=" . urlencode($message) . "&type=" . urlencode($message_type));
                exit();
            } else {
                $message = "Error resetting password. Please try again later.";
                $message_type = 'error';
                error_log("Password Reset PDO Error: " . implode(" ", $stmt->errorInfo()));
            }
        } catch (PDOException $e) {
            $message = "An unexpected database error occurred during password reset. Please try again later.";
            $message_type = 'error';
            error_log("Password Reset PDO Exception: " . $e->getMessage());
        }
    }
}
if (!isset($_POST['request_otp']) && !isset($_POST['reset_password']) && !isset($_SESSION['reset_otp'])) {
    unset($_SESSION['reset_otp']);
    unset($_SESSION['reset_otp_email']);
    unset($_SESSION['reset_otp_expires_at']);
}
require_once 'header.php';
ob_end_flush();
?>
<link rel="stylesheet" href="css/search.css">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<style>
    :root {
        --bg-primary: #0a0a0a;
        --bg-secondary: #111111;
        --bg-tertiary: #1a1a1a;
        --surface: #ffffff05;
        --surface-hover: #ffffff08;
        --border: #ffffff12;
        --border-hover: #ffffff20;
        --text-primary: #ffffff;
        --text-secondary: #a0a0a0;
        --text-tertiary: #707070;
        --accent: #298091ff;
        --accent-hover: #3d8294ff;
        --accent-secondary: #7c3aed;
        --success: #00ff88;
        --warning: #ffb800;
        --error: #ff4757;
        --glass-bg: rgba(255, 255, 255, 0.02);
        --glass-border: rgba(255, 255, 255, 0.1);
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --shadow-neon: 0 0 20px rgba(0, 212, 255, 0.3);
        --animated-bg-gradient-1: #10142c;
        --animated-bg-gradient-2: #1c0e2a;
    }

    html.light {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --bg-tertiary: #f1f3f4;
        --surface: #00000005;
        --surface-hover: #00000008;
        --border: #00000012;
        --border-hover: #00000020;
        --text-primary: #1a1a1a;
        --text-secondary: #4a4a4a;
        --text-tertiary: #6a6a6a;
        --accent: #0066cc;
        --accent-hover: #0052a3;
        --accent-secondary: #7c3aed;
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(0, 0, 0, 0.1);
        --shadow-neon: 0 0 20px rgba(0, 102, 204, 0.2);
        --animated-bg-gradient-1: #E6E6FA;
        --animated-bg-gradient-2: #F0F8FF;
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
        background: linear-gradient(-45deg, var(--animated-bg-gradient-1), var(--bg-primary), var(--animated-bg-gradient-2), var(--bg-primary));
        background-size: 500% 500%;
        animation: bg-gradient-animation 20s ease infinite;
        transition: background-color 0.3s, color 0.3s;
    }

    @media (max-width: 767px) {
        body {
            animation: none;
            background: var(--bg-primary);
        }
    }

    .font-space {
        font-family: 'Space Grotesk', monospace;
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
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        will-change: transform, box-shadow;
    }

    .glass-hover:hover {
        background: var(--surface-hover);
        border-color: var(--border-hover);
        transform: translateY(-1px) scale(1.01);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    html.light .glass-hover:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 2rem;
        font-weight: 500;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        overflow: hidden;
        will-change: transform, box-shadow;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        color: white;
        box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 212, 255, 0.4);
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--text-primary);
        border-color: var(--border);
    }

    .btn-secondary:hover {
        background: var(--surface-hover);
        border-color: var(--border-hover);
        transform: translateY(-2px);
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
        will-change: transform, background, border-color, box-shadow;
    }

    .card-animated-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), var(--accent-secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease-out;
    }

    .card-animated-border:hover::before {
        transform: scaleX(1);
    }

    .card:hover,
    .card-animated-border:hover {
        background: var(--surface-hover);
        border-color: var(--border-hover);
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    html.light .card:hover,
    html.light .card-animated-border:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea {
        transition: all 0.3s ease-in-out;
        will-change: border-color, box-shadow;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    textarea:focus {
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.2);
    }

    .skill-bar {
        width: 100%;
        height: 8px;
        background: var(--surface);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .skill-progress {
        height: 100%;
        background: linear-gradient(90deg, var(--accent), var(--accent-secondary));
        border-radius: 4px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        will-change: width;
    }

    .skill-progress::after {
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

    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.05;
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M20 20c0 11.046-8.954 20-20 20v-40c11.046 0 20 8.954 20 20z'/%3E%3C/g%3E%3C/svg%3E");
        z-index: -1;
        pointer-events: none;
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

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @media (min-width: 768px) {
        .typing-text {
            overflow: hidden;
            border-right: 2px solid var(--accent);
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: 0.05em;
            animation: typing 3.5s steps(40, end) forwards, blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        @keyframes blink-caret {

            from,
            to {
                border-color: transparent;
            }

            50% {
                border-color: var(--accent);
            }
        }
    }

    @media (min-width: 1024px) {
        .float-gentle {
            animation: float-gentle 6s ease-in-out infinite;
            will-change: transform;
        }

        @keyframes float-gentle {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }
    }

    @media (max-width: 1023px) {
        body {
            margin-left: 0 !important;
        }

        .main-content-wrapper {
            margin-left: 0;
            padding-top: 0;
        }

        .card,
        .card-animated-border {
            padding: 1.25rem;
        }

        .btn {
            padding: 0.625rem 1.5rem;
            font-size: 0.8rem;
        }
    }

    @media (min-width: 1024px) {
        .main-content-wrapper {
            margin-left: 15rem;
        }
    }

    #scrollTopBtn,
    #themeToggle {
        z-index: 1000;
    }

    .toast-container {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(0%);
        z-index: 1050;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: auto;
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
            left: 16px;
            right: 16px;
            bottom: 16px;
            width: auto;
            transform: none;
        }

        .toast {
            padding: 12px 16px;
            text-align: center;
            font-size: 16px;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<main class="flex-1 flex items-center justify-center px-4 py-8 lg:ml-60">
    <div id="toastContainer" class="toast-container"></div>
    <div class="w-full max-w-md animate-slide-in-fade">
        <div class="glassmorphic-bg rounded-2xl p-8 shadow-2xl">
            <header class="text-center mb-8">
                <h1 class="text-3xl font-bold gradient-text tracking-tight">Forgot Password?</h1>
                <p class="text-base text-[var(--text-secondary)] mt-2">Enter your email address to receive a password reset OTP.</p>
            </header>
            <form action="" method="POST" class="space-y-6" id="forgotPasswordForm" novalidate>
                <div>
                    <label for="email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-envelope text-[var(--text-secondary)]"></i></span>
                        <input type="email" id="email" name="email" class="w-full bg-[var(--surface)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition" placeholder="you@example.com" required value="<?php echo htmlspecialchars($email); ?>" <?php echo $otp_sent ? 'readonly' : ''; ?>>
                    </div>
                </div>
                <?php if (!$otp_sent): ?>
                    <button type="submit" name="request_otp" id="requestOtpButton" class="btn btn-primary w-full font-semibold py-3 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>Send OTP</span>
                    </button>
                <?php endif; ?>
                <?php if ($otp_sent): ?>
                    <div id="otpFields" class="space-y-6 animate__animated animate__fadeInDown">
                        <div>
                            <label for="otp" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">OTP</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-key text-[var(--text-secondary)]"></i></span>
                                <input type="text" id="otp" name="otp" class="w-full bg-[var(--surface)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition" placeholder="Enter OTP received in email" required>
                            </div>
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-lock text-[var(--text-secondary)]"></i></span>
                                <input type="password" id="new_password" name="new_password" class="w-full bg-[var(--surface)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-12 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition" placeholder="Create a strong new password" required value="<?php echo htmlspecialchars($new_password); ?>">
                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3" id="togglePassword"><i class="fas fa-eye text-[var(--text-secondary)]"></i></button>
                            </div>
                        </div>
                        <div>
                            <label for="confirm_new_password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Confirm New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-check-double text-[var(--text-secondary)]"></i></span>
                                <input type="password" id="confirm_new_password" name="confirm_new_password" class="w-full bg-[var(--surface)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition" placeholder="Repeat the new password" required value="<?php echo htmlspecialchars($confirm_new_password); ?>">
                            </div>
                        </div>
                        <button type="submit" name="reset_password" class="btn btn-primary w-full font-semibold py-3 flex items-center justify-center gap-2">
                            <i class="fas fa-key"></i>
                            <span>Reset Password</span>
                        </button>
                    </div>
                <?php endif; ?>
            </form>
            <p class="text-center text-sm text-[var(--text-secondary)] mt-8">
                Remembered your password?
                <a href="login.php" class="font-medium text-[var(--accent)] hover:underline">Back to Log in</a>
            </p>
        </div>
    </div>
</main>
<button id="themeToggle" class="fixed bottom-20 right-6 w-12 h-12 bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] text-[var(--text-primary)] rounded-full shadow-lg transition-all duration-300 flex items-center justify-center z-50 group">
    <i class="fas fa-moon group-hover:scale-110 transition-transform"></i>
</button>
<button id="scrollTopBtn" onclick="scrollToTop()" class="fixed bottom-6 right-6 w-12 h-12 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white rounded-full shadow-lg opacity-0 translate-y-4 pointer-events-none transition-all duration-300 flex items-center justify-center z-50 group">
    <i class="fas fa-arrow-up group-hover:scale-110 transition-transform"></i>
</button>
<script>
    AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-cubic'
    });
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
    let currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    const applyTheme = (theme) => {
        elements.html.classList.toggle('light', theme === 'light');
        const icon = elements.themeToggle.querySelector('i');
        icon.className = theme === 'light' ? 'fas fa-sun group-hover:scale-110 transition-transform' : 'fas fa-moon group-hover:scale-110 transition-transform';
        localStorage.setItem('theme', theme);
    };
    applyTheme(currentTheme);
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
    const animateSkillBars = () => {
        const skillBars = document.querySelectorAll('.skill-progress');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.width = entry.target.dataset.skillWidth;
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });
        skillBars.forEach(bar => observer.observe(bar));
    };
    document.addEventListener('DOMContentLoaded', () => {
        animateSkillBars();
        const form = document.getElementById('forgotPasswordForm');
        const emailInput = document.getElementById('email');
        const requestOtpButton = document.getElementById('requestOtpButton');
        const otpInput = document.getElementById('otp');
        const newPasswordInput = document.getElementById('new_password');
        const confirmNewPasswordInput = document.getElementById('confirm_new_password');
        const togglePassword = document.getElementById('togglePassword');

        function showToast(message, type = 'error') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-4 py-3 rounded-lg shadow-lg mb-4`;
            toast.textContent = message;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        <?php if (!empty($message)): ?>
            showToast("<?php echo htmlspecialchars($message); ?>", "<?php echo htmlspecialchars($message_type); ?>");
        <?php endif; ?>
        if (requestOtpButton) {
            const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            requestOtpButton.disabled = !isValidEmail;
            emailInput.addEventListener('input', function() {
                const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
                requestOtpButton.disabled = !isValidEmail;
            });
        }
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            const submitterName = event.submitter ? event.submitter.name : null;
            if (submitterName === 'request_otp') {
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                    showToast('Please enter a valid email address.');
                    isValid = false;
                }
            } else if (submitterName === 'reset_password') {
                if (!otpInput || !otpInput.value.trim()) {
                    showToast('Please enter the OTP.');
                    isValid = false;
                }
                if (!passwordPattern.test(newPasswordInput.value)) {
                    showToast('New password must be at least 8 characters long and contain both letters and numbers.');
                    isValid = false;
                }
                if (newPasswordInput.value !== confirmNewPasswordInput.value) {
                    showToast('New passwords do not match.');
                    isValid = false;
                }
            }
            if (!isValid) {
                event.preventDefault();
            }
        });
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = newPasswordInput.type === 'password' ? 'text' : 'password';
                newPasswordInput.type = type;
                confirmNewPasswordInput.type = type;
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
</body>

</html>