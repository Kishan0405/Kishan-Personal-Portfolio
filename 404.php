<?php
ob_start();
require_once 'includes/auth.php';
include_once 'header.php';

$pageTitle = "404 Not Found – Kishan Raj Portfolio";
?>

<main class="main-content-wrapper flex-1 flex flex-col items-center justify-center px-4 lg:ml-60 relative min-h-[calc(100vh-8rem)] overflow-hidden" data-aos="fade-in">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] md:w-[500px] md:h-[500px] bg-[var(--accent)]/10 rounded-full blur-[80px] -z-10 pointer-events-none"></div>

    <div class="animate-slide-in-fade w-full max-w-lg text-center relative z-10">

        <div class="glass rounded-2xl p-8 sm:p-12 shadow-2xl relative overflow-hidden border border-[var(--glass-border)] group hover:shadow-[var(--accent)]/10 transition-shadow duration-500">

            <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none"
                style="background-image: radial-gradient(circle at 50% 0%, var(--accent) 0%, transparent 60%);">
            </div>

            <div class="mb-8 relative z-10 inline-block">
                <div class="w-24 h-24 mx-auto bg-[var(--surface-hover)] rounded-full flex items-center justify-center shadow-inner border border-[var(--border)] relative">
                    <div class="absolute inset-0 rounded-full bg-[var(--accent)]/20 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <i class="fas fa-ghost text-5xl text-[var(--accent)] transform group-hover:-translate-y-1 transition-transform duration-300"></i>
                </div>
            </div>

            <h1 class="text-6xl sm:text-8xl font-bold gradient-text tracking-tighter font-space mb-2 relative z-10 drop-shadow-lg">
                404
            </h1>

            <h2 class="text-2xl font-semibold text-[var(--text-primary)] mb-3 relative z-10">Page Not Found</h2>

            <p class="text-[var(--text-secondary)] mb-8 leading-relaxed relative z-10 text-sm sm:text-base max-w-xs mx-auto sm:max-w-none">
                Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
                <a href="/" class="btn btn-secondary font-medium py-3.5 px-8 rounded-lg flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-home"></i>
                    <span>Go Home</span>
                </a>

                <a href="contact.php" class="btn btn-secondary font-medium py-3.5 px-8 rounded-lg flex items-center justify-center gap-2 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-life-ring"></i>
                    <span>Support</span>
                </a>
            </div>

        </div>
    </div>
</main>

<script>
    if (typeof AOS !== 'undefined') {
        AOS.init();
    }
</script>

<?php
ob_end_flush();
require 'footer.php';
?>