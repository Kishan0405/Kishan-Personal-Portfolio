<?php
ob_start();

require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$usernameOrEmail = '';

if (isset($_GET['username'])) {
    $usernameOrEmail = sanitize($_GET['username']);
} elseif (isset($_GET['email'])) {
    $usernameOrEmail = sanitize($_GET['email']);
}

if (isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = $_GET['redirect'];
}

if (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = sanitize($_POST['username_or_email']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, role, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            session_regenerate_id(true);

            if (isset($_SESSION['redirect_url'])) {
                $redirect_url = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                header("Location: " . $redirect_url);
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = "Invalid login credentials. Please check your username/email and password.";
            header("Location: login.php?error=" . urlencode($message) . "&username=" . urlencode($usernameOrEmail));
            exit();
        }
    } catch (PDOException $e) {
        error_log("Login PDO Error: " . $e->getMessage(), 0);
        $message = "An unexpected error occurred. Please try again later.";
        header("Location: login.php?error=" . urlencode($message));
        exit();
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/auth.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
</head>

<body>
    <main class="main-content-wrapper flex-1 flex items-center justify-center px-4 py-8" data-aos="fade-up">
        <div class="animate-slide-in-fade">
            <div class="glass rounded-2xl p-8 shadow-2xl lg:w-[600px]">
                <header class="text-center mb-8">
                    <h1 class="text-3xl font-bold gradient-text tracking-tight font-space">Welcome Back!</h1>
                    <p class="text-base text-[var(--text-secondary)] mt-2">Log in with your QuizzletMaster account.</p>
                </header>

                <?php if (!empty($message)): ?>
                    <div class="bg-[var(--error)]/10 border border-[var(--error)]/30 text-[var(--error)] px-4 py-3 rounded-lg relative mb-6" role="alert">
                        <span class="block sm:inline"><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>

                <form action="login" method="POST" class="space-y-6">
                    <div>
                        <label for="username_or_email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Username or Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-user text-[var(--text-tertiary)]"></i>
                            </span>
                            <input type="text" id="username_or_email" name="username_or_email"
                                class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition"
                                placeholder="e.g., username or you@example.com"
                                required value="<?php echo htmlspecialchars($usernameOrEmail); ?>">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-[var(--text-secondary)]">Password</label>
                            <a href="forgotpassword.php" class="text-sm text-[var(--accent)] hover:underline">Forgot Password?</a>
                        </div>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-[var(--text-tertiary)]"></i>
                            </span>
                            <input type="password" id="password" name="password"
                                class="w-full bg-[var(--bg-tertiary)] border border-[var(--border)] text-[var(--text-primary)] rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-0 focus:border-[var(--accent)] transition"
                                placeholder="Enter Password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full text-white font-semibold py-3 flex items-center justify-center gap-2">
                        <i class="fas fa-right-to-bracket"></i>
                        <span>Log In</span>
                    </button>
                </form>

                <p class="text-center text-sm text-[var(--text-secondary)] mt-8">
                    Don't have an account?
                    <a href="register.php" class="font-medium text-[var(--accent)] hover:underline">Sign up</a>
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
    </script>
</body>

</html>

<?php
include_once 'footer.php';
ob_end_flush();
?>