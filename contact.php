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
    <link rel="stylesheet" href="css/search.css">
    <link rel="icon" type="image/svg+xml" href="includes/kishanraj.svg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --accent: #00d4ff;
            --accent-hover: #00b8e6;
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
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .glass-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        html.light .glass-hover:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
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

        .project-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            will-change: transform, background, border-color, box-shadow;
        }

        .project-card:hover {
            background: var(--surface-hover);
            border-color: var(--border-hover);
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        html.light .project-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
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

        @media (max-width: 1023px) {
            body {
                margin-left: 0 !important;
            }

            .main-content-wrapper {
                margin-left: 0;
                padding-top: 0;
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
    </style>
</head>

<body class="bg-bg-primary text-text-primary">
    <main class="main-content-wrapper flex-1 px-4 py-8 mt-0">
        <section class="max-w-6xl mx-auto mb-16 mt-0 text-center lg:mr-10" data-aos="fade-up">
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

                        <a href="http://t.me/kishanbantakal" target="_blank" rel="noopener noreferrer" aria-label="Telegram Profile" class="group w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--accent)] transition duration-200">
                            <i class="fab fa-telegram-plane text-base sm:text-xl group-hover:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <footer class="text-center py-8 mt-20 border-t border-[var(--border)]">
            <div class="max-w-6xl mx-auto">
                <p class="text-[var(--text-secondary)] mb-4">
                    Kishan Raj Personal Website
                </p>
                <p class="text-[var(--text-secondary)] mb-4">
                    © 2024 - <?php echo date("Y"); ?>
                </p>
                <p class="text-[var(--text-secondary)] mb-4">
                    Designed and Developed with
                </p>
                <p class="text-[var(--text-tertiary)] text-sm space-x-2">
                    <i class="fab fa-php text-indigo-600" aria-hidden="true"></i> PHP
                    <i class="fab fa-html5 text-orange-600" aria-hidden="true"></i> HTML
                    <i class="fa-brands fa-css3-alt text-blue-500" aria-hidden="true"></i> CSS
                    <img src="https://tailwindcss.com/favicons/favicon-32x32.png" alt="Tailwind CSS logo" class="w-4 h-4 inline align-text-bottom"> Tailwind
                    <i class="fab fa-js-square text-yellow-500" aria-hidden="true"></i> JS
                    <i class="fab fa-google text-red-500" aria-hidden="true"></i> Google
                </p>
            </div>
        </footer>
    </main>

    <button id="themeToggle" aria-label="Toggle Theme" class="fixed bottom-20 right-6 w-12 h-12 bg-[var(--surface)] hover:bg-[var(--surface-hover)] border border-[var(--border)] text-[var(--text-primary)] rounded-full shadow-lg transition-all duration-300 flex items-center justify-center z-50 group">
        <i class="fas fa-moon group-hover:scale-110 transition-transform"></i>
    </button>

    <button id="scrollTopBtn" onclick="scrollToTop()" aria-label="Scroll to Top" class="fixed bottom-6 right-6 w-12 h-12 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white rounded-full shadow-lg opacity-0 translate-y-4 pointer-events-none transition-all duration-300 flex items-center justify-center z-50 group">
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
    </script>
</body>

</html>