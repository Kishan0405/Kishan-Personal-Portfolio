<?php
$startYear = 2024;
$currentYear = date("Y");
$yearText = ($startYear == $currentYear) ? $startYear : $startYear . ' - ' . $currentYear;
?>

<!DOCTYPE html>
<html lang="en">

<body>

    <style>
        :root {
            --surface-dark: #111;
            --surface-muted: #191919;
            --surface: #222;
            --surface-hover: #333;
            --border: #333;
            --text-primary: #f0f0f0;
            --text-secondary: #aaa;
            --text-tertiary: #777;
            --accent: #007bff;
            --accent-hover: #0056b3;
        }

        html.light {
            --surface-dark: #f0f0f0;
            --surface-muted: #f9f9f9;
            --surface: #ffffff;
            --surface-hover: #f0f0f0;
            --border: #e0e0e0;
            --text-primary: #222;
            --text-secondary: #555;
            --text-tertiary: #888;
        }

        .floating-btn {
            position: fixed;
            right: 1.5rem;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            cursor: pointer;
            border: 1px solid var(--border);
            color: var(--text-primary);
            background-color: var(--surface);
        }

        .floating-btn:hover {
            background-color: var(--surface-hover);
            transform: scale(1.05);
        }

        .floating-btn:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        .floating-btn i {
            transition: transform 0.2s ease;
        }

        .floating-btn:hover i {
            transform: scale(1.1);
        }

        #themeToggle {
            bottom: 6rem;
        }

        #scrollTopBtn {
            bottom: 1.5rem;
            background-color: var(--accent);
            color: white;
            border-color: var(--accent);
            opacity: 0;
            transform: translateY(1rem);
            pointer-events: none;
        }

        #scrollTopBtn:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        #scrollTopBtn:focus-visible {
            outline-color: white;
        }

        #scrollTopBtn.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .translate-wrapper {
            position: relative;
            display: inline-block;
        }

        .translate-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--surface);
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 9999px;
            gap: 10px;
            transition: border-color 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .translate-container:hover {
            border-color: var(--text-tertiary);
            background-color: var(--surface-hover);
        }

        .translate-icon {
            color: var(--accent);
            font-size: 1.1rem;
        }

        .current-lang-text {
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
        }

        .language-menu {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%) scale(0.95);
            width: 200px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease-in-out;
            z-index: 100;
        }

        .language-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1);
        }

        .lang-option {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px 12px;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }

        .lang-option:hover {
            background-color: var(--surface-hover);
            color: var(--text-primary);
        }

        .lang-option.selected {
            background-color: rgba(0, 123, 255, 0.1);
            color: var(--accent);
            font-weight: 600;
        }

        #google_translate_element {
            display: none;
            height: 0;
            width: 0;
            overflow: hidden;
        }

        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        body {
            top: 0px !important;
            position: static !important;
        }

        #goog-gt-tt,
        .goog-te-balloon-frame {
            display: none !important;
        }

        .goog-text-highlight {
            background: none !important;
            box-shadow: none !important;
        }

        font {
            background-color: transparent !important;
            box-shadow: none !important;
            color: inherit !important;
        }
    </style>

    <button id="themeToggle" class="floating-btn" aria-pressed="false" aria-label="Toggle color theme">
        <i class=""></i>
    </button>

    <button id="scrollTopBtn" class="floating-btn" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>


    <footer class="bg-[var(--bg-secondary)] border-t border-[var(--border)] pt-12 pb-8 lg:ml-60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center py-8 border-t border-[var(--border)]" class="max-w-6xl mx-auto">

                <div class="mb-8 flex flex-col items-center">
                    <p class="text-[var(--text-tertiary)] text-xs uppercase tracking-wider mb-3">Select Language</p>

                    <div class="translate-wrapper">
                        <div class="translate-container" id="langToggleBtn">
                            <i class="fas fa-globe translate-icon"></i>
                            <span class="current-lang-text" id="currentLangLabel">English</span>
                            <i class="fas fa-chevron-up text-[var(--text-tertiary)] text-xs ml-2"></i>
                        </div>

                        <div class="language-menu" id="customLangMenu">
                            <button class="lang-option" data-lang="en">English</button>
                            <button class="lang-option" data-lang="kn">ಕನ್ನಡ (Kannada)</button>
                            <button class="lang-option" data-lang="ml">മലയാളം (Malayalam)</button>
                            <button class="lang-option" data-lang="ta">தமிழ் (Tamil)</button>
                            <button class="lang-option" data-lang="te">తెలుగు (Telugu)</button>
                        </div>
                    </div>

                    <div id="google_translate_element"></div>
                </div>

                <p class="text-[var(--text-secondary)] mb-4">
                    Kishan Raj Personal Website
                </p>
                <p class="text-[var(--text-secondary)] mb-4">
                    © <?php echo $yearText; ?>
                </p>
                <p class="text-[var(--text-secondary)] mb-4">
                    Designed and Developed with
                </p>
                <p class="text-[var(--text-tertiary)] text-sm flex justify-center gap-3 flex-wrap items-center">
                    <span><i class="fab fa-php text-indigo-600"></i> PHP</span>
                    <span><i class="fab fa-html5 text-orange-600"></i> HTML</span>
                    <span><i class="fa-brands fa-css3-alt text-blue-500"></i> CSS</span>
                    <span class="flex items-center gap-1"><img src="https://tailwindcss.com/favicons/favicon-32x32.png" alt="Tailwind" class="w-4 h-4"> Tailwind</span>
                    <span><i class="fab fa-js-square text-yellow-500"></i> JS</span>
                    <span><i class="fab fa-google text-red-500"></i> Google</span>
                </p>
            </div>

        </div>

        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,kn,ml,ta,te',
                    autoDisplay: false
                }, 'google_translate_element');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggleBtn = document.getElementById('langToggleBtn');
                const menu = document.getElementById('customLangMenu');
                const options = document.querySelectorAll('.lang-option');
                const currentLabel = document.getElementById('currentLangLabel');

                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.classList.toggle('active');
                });

                document.addEventListener('click', (e) => {
                    if (!toggleBtn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.remove('active');
                    }
                });

                options.forEach(option => {
                    option.addEventListener('click', () => {
                        const langCode = option.getAttribute('data-lang');
                        const langName = option.innerText.split(' ')[0];
                        triggerGoogleTranslate(langCode);
                        currentLabel.innerText = (langCode === 'en') ? 'English' : langName;
                        options.forEach(opt => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        menu.classList.remove('active');
                    });
                });
            });

            function triggerGoogleTranslate(langCode) {
                const combo = document.querySelector('.goog-te-combo');
                if (combo) {
                    combo.value = langCode;
                    combo.dispatchEvent(new Event('change'));
                }
            }

            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    easing: 'ease-out-cubic'
                });
            }

            const elements = {
                scrollTopBtn: document.getElementById('scrollTopBtn'),
                themeToggle: document.getElementById('themeToggle'),
                html: document.documentElement
            };

            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            if (elements.scrollTopBtn) {
                elements.scrollTopBtn.addEventListener('click', scrollToTop);
            }

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    elements.scrollTopBtn.classList.add('visible');
                } else {
                    elements.scrollTopBtn.classList.remove('visible');
                }
            });

            let currentTheme = localStorage.getItem('theme') || 'dark';

            const applyTheme = (theme) => {
                elements.html.classList.toggle('light', theme === 'light');
                elements.html.classList.toggle('dark', theme === 'dark');

                const icon = elements.themeToggle.querySelector('i');
                const iconClass = theme === 'light' ? 'fas fa-sun' : 'fas fa-moon';
                icon.className = `${iconClass} text-xl`;

                elements.themeToggle.setAttribute('aria-pressed', theme === 'dark');
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
                if (skillBars.length === 0) return;

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

                if (window.innerWidth >= 768) {
                    setTimeout(() => {
                        const typingElement = document.querySelector('.typing-text');
                        if (typingElement) {
                            typingElement.classList.remove('typing-text');
                            void typingElement.offsetWidth;
                            typingElement.classList.add('typing-text');
                        }
                    }, 1000);
                }
            });
        </script>
</body>

</html>