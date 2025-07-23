<?php

include_once 'header.php';

$pageTitle = "404 Not Found - Kishan Raj";
$pageDescription = "The page you are looking for on Kishan Raj's website does not exist.";

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
        textarea {
            transition: all 0.3s ease-in-out;
            will-change: border-color, box-shadow;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
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
    </style>
</head>

<body class="bg-bg-primary text-text-primary">
    <main class="main-content-wrapper flex-1 px-2 py-8">
        <section class="relative mb-20 items-center justify-center text-center" data-aos="fade-up">
            <div class="hero-pattern"></div>
            <div class="glass rounded-3xl p-6 md:p-12 relative overflow-hidden max-w-xl mx-auto">
                <h1 class="text-6xl sm:text-7xl lg:text-8xl font-bold gradient-text mb-4 font-space" data-aos="fade-up">
                    404
                </h1>
                <p class="text-2xl md:text-3xl text-[var(--text-secondary)] mb-6" data-aos="fade-up" data-aos-delay="200">
                    Page Not Found
                </p>
                <p class="text-base lg:text-lg text-[var(--text-tertiary)] mb-8 leading-relaxed mx-auto" data-aos="fade-up" data-aos-delay="300">
                    Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i> Go to Homepage
                    </a>
                    <a href="contact.php" class="btn btn-secondary">
                        <i class="fas fa-question-circle"></i> Contact Support
                    </a>
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

        document.addEventListener('DOMContentLoaded', () => {});
    </script>
</body>

</html>