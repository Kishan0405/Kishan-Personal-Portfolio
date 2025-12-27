<?php
include_once 'header.php';

$pageTitle = "Contact Kishan Raj - Biotechnology Engineer & Web Developer";
$pageDescription = "Get in touch with Kishan Raj for collaborations, project inquiries, or any questions related to web development, biotechnology, and AI solutions.";

if (!isset($userProfilePicturePath)) {
    $userProfilePicturePath = "https://via.placeholder.com/256/00d4ff/00b8e6?text=KR";
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
</head>

<body class="bg-bg-primary text-text-primary">
    <main class="main-content-wrapper flex-1 px-4 py-8 mt-0">
        <section class="max-w-6xl mx-auto mb-16 mt-0 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold gradient-text mb-4 font-space">Let’s Connect</h1>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto mb-10">
                Feel free to drop a message for collaboration, queries, or just to say hi!
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-10 md:gap-12 px-2 sm:px-4">
                
                <div class="glass glass-hover text-left space-y-6 p-6 sm:p-8 rounded-xl" data-aos="fade-right" data-aos-delay="200">
                    <h2 class="text-xl sm:text-2xl font-semibold mb-4 text-[var(--text-primary)]">Contact Details</h2>
                    <address class="not-italic space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="icon w-10 h-10 sm:w-11 sm:h-11 text-[var(--accent)] border border-[var(--accent)]/20 rounded-full bg-[var(--accent)]/10 flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-google text-sm sm:text-base" aria-hidden="true"></i>
                            </div>
                            <div>
                                <p class="text-sm text-[var(--text-secondary)]">Email</p>
                                <a href="mailto:kishanbantakal@gmail.com" class="text-base text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">kishanbantakal@gmail.com</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="icon w-10 h-10 sm:w-11 sm:h-11 text-[var(--accent)] border border-[var(--accent)]/20 rounded-full bg-[var(--accent)]/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-sm sm:text-base" aria-hidden="true"></i>
                            </div>
                            <div>
                                <p class="text-sm text-[var(--text-secondary)]">Phone</p>
                                <a href="tel:+917338323960" class="text-base text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">+91 73383 23960</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="icon w-10 h-10 sm:w-11 sm:h-11 text-[var(--accent)] border border-[var(--accent)]/20 rounded-full bg-[var(--accent)]/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-sm sm:text-base" aria-hidden="true"></i>
                            </div>
                            <div>
                                <p class="text-sm text-[var(--text-secondary)]">Location</p>
                                <p class="text-base text-[var(--text-primary)]">Udupi, Karnataka, India</p>
                            </div>
                        </div>
                    </address>
                </div>

                <div class="glass glass-hover text-left space-y-6 p-6 sm:p-8 rounded-xl" data-aos="fade-left" data-aos-delay="200">
                    <h2 class="text-xl sm:text-2xl font-semibold mb-4 text-[var(--text-primary)]">Social Profiles</h2>
                    <div class="flex flex-wrap gap-4 sm:gap-5">
                        <a href="https://www.linkedin.com/in/kishanbantakal/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn Profile" class="group w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">
                            <i class="fab fa-linkedin-in text-base sm:text-xl group-hover:scale-110 transition-transform"></i>
                        </a>

                        <a href="https://github.com/Kishan0405" target="_blank" rel="noopener noreferrer" aria-label="GitHub Profile" class="group w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">
                            <i class="fab fa-github text-base sm:text-xl group-hover:scale-110 transition-transform"></i>
                        </a>

                        <a href="https://t.me/kishanbantakal" target="_blank" rel="noopener noreferrer" aria-label="Telegram Profile" class="group w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">
                            <i class="fab fa-telegram-plane text-base sm:text-xl group-hover:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            disable: window.innerWidth < 768
        });
    </script>
</body>

</html>

<?php include_once 'footer.php'; ?>