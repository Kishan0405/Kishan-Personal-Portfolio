<?php
ob_start();
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'includes/PHPMailer/src/Exception.php';
require_once 'includes/PHPMailer/src/PHPMailer.php';
require_once 'includes/PHPMailer/src/SMTP.php';
require_once 'header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
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
        $message = "Invalid email address format.";
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
</head>
<body style="background-color: #f1f5f9; font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #0f172a; margin-bottom: 20px;">Password Reset Request</h2>
        <p style="color: #1e293b;">We received a request to reset the password for <strong>{$safe_email}</strong>.</p>
        <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0;">
            <p style="font-size: 12px; color: #64748b; text-transform: uppercase; margin: 0;">Your OTP Code</p>
            <p style="font-size: 32px; font-weight: bold; color: #6a0dad; letter-spacing: 5px; margin: 10px 0 0;">{$otp}</p>
        </div>
        <p style="color: #64748b; font-size: 14px;">Valid for 10 minutes. If you did not request this, please ignore this email.</p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        <p style="text-align: center; color: #94a3b8; font-size: 12px;">© {$year} QuizzletMaster</p>
    </div>
</body>
</html>
HTML;
                $altBody = "Your QuizzletMaster Password Reset OTP is: {$otp}. Valid for 10 minutes.";

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = ''; // ADD EMAIL AFTER 2 FACTOR AUTHENTICATION ENABLED
                $mail->Password = ''; // ADD APP PASSWORD HERE
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('', 'QuizzletMaster'); // ADD SENDER EMAIL
                $mail->addAddress($email_to_send);
                $mail->isHTML(true);
                $mail->Subject = 'QuizzletMaster Password Reset OTP';
                $mail->Body = $emailBody;
                $mail->AltBody = $altBody;
                $mail->send();

                $message = 'OTP sent successfully! Please check your inbox.';
                $message_type = 'success';
                $otp_sent = true;
            }
        } catch (Exception $e) {
            $message = "Mailer Error: " . $e->getMessage();
            $message_type = 'error';
        } catch (PDOException $e) {
            $message = "Database error. Please try again.";
            $message_type = 'error';
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
        $message = "Password must be 8+ chars with letters & numbers.";
        $message_type = 'error';
    } elseif (!isset($_SESSION['reset_otp']) || $_SESSION['reset_otp'] !== $otp_input) {
        $message = "Invalid or expired OTP.";
        $message_type = 'error';
    } elseif (strtotime($_SESSION['reset_otp_expires_at']) < time()) {
        $message = "OTP has expired. Please request a new one.";
        $message_type = 'error';
        unset($_SESSION['reset_otp'], $_SESSION['reset_otp_email']);
    } else {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($stmt->execute([$hashed_password, $email_input])) {
                unset($_SESSION['reset_otp'], $_SESSION['reset_otp_email'], $_SESSION['reset_otp_expires_at']);

                $message = "Password reset successfully. You can now log in.";
                header("Location: login.php?message=" . urlencode($message) . "&type=success");
                exit();
            } else {
                $message = "Error updating password.";
            }
        } catch (PDOException $e) {
            $message = "Database error during reset.";
            error_log($e->getMessage());
        }
    }
}

if (!isset($_POST['request_otp']) && !isset($_POST['reset_password']) && !isset($_SESSION['reset_otp'])) {
    unset($_SESSION['reset_otp']);
    unset($_SESSION['reset_otp_email']);
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - QuizzletMaster</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/auth.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
</head>

<body>
    <main class="main-content-wrapper flex-1 flex items-center justify-center px-4 py-8" data-aos="fade-up">
        <div class="animate-slide-in-fade">
            <div class="glass rounded-2xl p-8 shadow-2xl lg:w-[600px]">

                <header class="text-center mb-8">
                    <h1 class="text-3xl font-bold gradient-text tracking-tight font-space">
                        <?php echo $otp_sent ? 'Verify OTP' : 'Forgot Password?'; ?>
                    </h1>
                    <p class="text-base text-[var(--text-secondary)] mt-2">
                        <?php echo $otp_sent ? 'Enter the code sent to your email.' : 'Enter your email to receive a reset code.'; ?>
                    </p>
                </header>

                <?php if (!empty($message)): ?>
                    <div class="<?php echo $message_type === 'success' ? 'bg-green-500/10 border-green-500/30 text-green-500' : 'bg-[var(--error)]/10 border-[var(--error)]/30 text-[var(--error)]'; ?> border px-4 py-3 rounded-lg relative mb-6 text-sm" role="alert">
                        <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                        <span class="block sm:inline"><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-6" id="authForm" novalidate>

                    <div>
                        <label for="email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-envelope text-[var(--text-tertiary)]"></i>
                            </span>
                            <input type="email" id="email" name="email"
                                class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition"
                                placeholder="you@example.com"
                                required
                                value="<?php echo htmlspecialchars($email); ?>"
                                <?php echo $otp_sent ? 'readonly' : ''; ?>>
                        </div>
                    </div>

                    <?php if (!$otp_sent): ?>
                        <button type="submit" name="request_otp" class="btn btn-primary w-full text-white font-semibold py-3 flex items-center justify-center gap-2 transition-all hover:scale-[1.02]">
                            <span>Send OTP Code</span>
                        </button>
                    <?php endif; ?>

                    <?php if ($otp_sent): ?>
                        <div class="space-y-6 animate__animated animate__fadeIn">

                            <div>
                                <label for="otp" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">OTP Code</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-key text-[var(--text-tertiary)]"></i>
                                    </span>
                                    <input type="text" id="otp" name="otp"
                                        class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition tracking-widest"
                                        placeholder="123456" required>
                                </div>
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">New Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-lock text-[var(--text-tertiary)]"></i>
                                    </span>
                                    <input type="password" id="new_password" name="new_password"
                                        class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-12 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition"
                                        placeholder="Min 8 chars (A-Z & 0-9)" required>

                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="togglePassword">
                                        <i class="fas fa-eye text-[var(--text-tertiary)] hover:text-[var(--accent)] transition"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="confirm_new_password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Confirm Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-check-double text-[var(--text-tertiary)]"></i>
                                    </span>
                                    <input type="password" id="confirm_new_password" name="confirm_new_password"
                                        class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition"
                                        placeholder="Re-enter password" required>
                                </div>
                            </div>

                            <button type="submit" name="reset_password" class="btn btn-primary w-full text-white font-semibold py-3 flex items-center justify-center gap-2 transition-all hover:scale-[1.02]">
                                <i class="fas fa-sync-alt"></i>
                                <span>Reset Password</span>
                            </button>
                        </div>
                    <?php endif; ?>

                </form>

                <p class="text-center text-sm text-[var(--text-secondary)] mt-8">
                    Remembered your password?
                    <a href="login.php" class="font-medium text-[var(--accent)] hover:underline transition-colors">Back to Log in</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        AOS.init({
            duration: 600,
            once: true,
            easing: 'ease-out-cubic'
        });

        const toggleBtn = document.getElementById('togglePassword');
        if (toggleBtn) {
            const passInput = document.getElementById('new_password');
            const confirmInput = document.getElementById('confirm_new_password');

            toggleBtn.addEventListener('click', function() {
                const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passInput.setAttribute('type', type);
                confirmInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    </script>
</body>

</html>

<?php
require_once 'footer.php';
ob_end_flush();
?>