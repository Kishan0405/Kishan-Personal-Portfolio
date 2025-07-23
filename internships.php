<?php
include_once 'header.php';
$pageTitle = "Kishan Raj - Internship Experience";
$pageDescription = "A collection of my internship reports, presentations, and photo galleries showcasing my work and experiences during my internship period.";
if (!isset($userProfilePicturePath)) {
    $userProfilePicturePath = "https://via.placeholder.com/256/00d4ff/00b8e6?text=KR";
}
$internshipItems = [
    [
        'title' => 'Summer Internship 2024-25 Phase 1 PPT',
        'description' => 'This is an internship done in our college for year 2024-25 Phase 1. The presentation covers the entire my batchmates who worked on this project and mine as a team. This helped to learn more on the part of the data analysis and research the articles to train a AI model. Hope our project becomes successful',
        'link' => 'includes/internship_docs/Summer Internship 2024-25 Phase 1 PPT.pdf',
        'download_link' => 'includes/internship_docs/Summer Internship 2024-25 Phase 1 PPT.pdf',
        'images' => [
            'includes/internship_docs/no_image.gif',
        ],
        'tags' => ['Phase 1 Presentation', 'Documentation', '2025', 'PPT']
    ],
    [
        'title' => 'Activity Based Internship Final Presentation',
        'description' => 'My final Presentation for 2023-24 Activity Based Internship, detailing all research (activity based) & tasks, learnings and outcomes. This presentation covers the entire overview of the activity based internship, challenges faced in India by common people and machinaries/tools acquired by common people in the field of agriculture.',
        'link' => 'includes/internship_docs/Internship-2023-24 Original PPT.pdf',
        'download_link' => 'includes/internship_docs/Internship-2023-24 Original PPT.pdf',
        'images' => [
            'includes/internship_docs/no_image.gif',
        ],
        'tags' => ['Final Report', 'Documentation', '2024', 'PPT']
    ],
    [
        'title' => 'Activity Based Internship Final Report',
        'description' => 'My final report for 2023-24 Activity Based Internship, detailing all research (activity based) & tasks, learnings and outcomes. This document covers the entire overview of the activity based internship, challenges faced in India by common people and machinaries/tools acquired by common people in the field of agriculture.',
        'link' => 'includes/internship_docs/Internship-2023-24 Original.pdf',
        'download_link' => 'includes/internship_docs/Internship-2023-24 Original.pdf',
        'images' => [
            'includes/internship_docs/image_01.png',
        ],
        'tags' => ['Final Report', 'Documentation', '2024', 'PDF']
    ],
];
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

        .lightbox-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .lightbox-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 0.5rem;
            transition: opacity 0.3s ease;
            will-change: opacity;
        }

        .lightbox-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-nav-btn:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: translateY(-50%) scale(1.05);
        }

        .lightbox-nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .lightbox-nav-btn.prev {
            left: 1rem;
        }

        .lightbox-nav-btn.next {
            right: 1rem;
        }

        .lightbox-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 0.5rem 0.8rem;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.25rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-close-btn:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: scale(1.05);
        }

        .lightbox-caption {
            color: white;
            margin-top: 1rem;
            text-align: center;
            max-width: 80%;
            font-size: 1rem;
        }

        .lightbox-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid var(--accent);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lightbox-spinner.show {
            opacity: 1;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-bg-primary text-text-primary">
    <main class="main-content-wrapper flex-1 px-4 py-8 mt-0">
        <section class="max-w-6xl mx-auto mb-16 mt-0 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold gradient-text mb-4 font-space">Internship Journey</h1>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto">
                A collection of my internship reports, presentations and visual insights.
            </P>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto">
                Each artifact represents a milestone in my professional development and learning.
            </p>
        </section>
        <section class="max-w-7xl mx-auto">
            <?php if (empty($internshipItems)) : ?>
                <div class="text-center py-20 px-4 bg-[var(--surface)] border border-[var(--border)] rounded-xl">
                    <i class="fas fa-box-open text-[var(--text-tertiary)] text-6xl mb-4"></i>
                    <h2 class="text-3xl font-bold text-[var(--text-primary)] mb-2 font-space">No Internship Items Yet</h2>
                    <p class="text-[var(--text-secondary)] text-lg">
                        It looks like there are no internship documents or galleries to display at the moment. Please check back later!
                    </p>
                </div>
            <?php else : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    <?php foreach ($internshipItems as $index => $item) : ?>
                        <?php
                        // Determine the main image source and alt text
                        $mainImageSrc = !empty($item['images']) ? htmlspecialchars($item['images'][0]) : 'https://via.placeholder.com/600x400/CCCCCC/888888?text=No+Image';
                        $mainImageAlt = !empty($item['images']) ? htmlspecialchars($item['title'] . ' main preview') : 'No image available for ' . htmlspecialchars($item['title']);
                        ?>
                        <div class="project-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 150; ?>">
                            <div class="relative overflow-hidden rounded-t-lg group">
                                <img src="<?php echo $mainImageSrc; ?>" alt="<?php echo $mainImageAlt; ?>" class="w-full h-64 object-cover object-center transition-transform duration-500 ease-in-out group-hover:scale-105">

                                <?php if (!empty($item['images'])) : // Only show the button if there are images 
                                ?>
                                    <?php if (count($item['images']) > 1) : ?>
                                        <button class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-lg font-bold" onclick="openLightbox(<?php echo htmlspecialchars(json_encode($item['images'])); ?>, 0)" aria-label="View gallery of <?php echo htmlspecialchars($item['title']); ?>">
                                            <i class="fas fa-images mr-2" aria-hidden="true"></i> View Gallery (<?php echo count($item['images']); ?>)
                                        </button>
                                    <?php else : ?>
                                        <button class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-lg font-bold" onclick="openLightbox([<?php echo htmlspecialchars(json_encode($item['images'][0])); ?>], 0)" aria-label="Enlarge image of <?php echo htmlspecialchars($item['title']); ?>">
                                            <i class="fas fa-expand mr-2" aria-hidden="true"></i> Enlarge Image
                                        </button>
                                    <?php endif; ?>
                                <?php endif; // End check for empty images 
                                ?>
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-2xl font-bold mb-3 font-space text-[var(--text-primary)]"><?php echo htmlspecialchars($item['title']); ?></h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($item['tags'] as $tag) : ?>
                                        <span class="px-3 py-1 bg-[var(--accent)]/10 text-[var(--accent)] text-xs font-semibold rounded-full border border-[var(--accent)]/20 shadow-sm"><?php echo htmlspecialchars($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="text-[var(--text-secondary)] leading-relaxed mb-6 flex-grow">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </p>
                                <div class="mt-auto flex flex-col sm:flex-row gap-3">
                                    <?php
                                    $isPdf = pathinfo($item['link'], PATHINFO_EXTENSION) === 'pdf';
                                    $viewButtonText = $isPdf ? 'View Document' : 'View Online';
                                    $downloadButtonText = 'Download PDF';
                                    ?>
                                    <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-full sm:w-auto justify-center" aria-label="<?php echo $viewButtonText . ' for ' . htmlspecialchars($item['title']); ?>">
                                        <i class="fas fa-eye" aria-hidden="true"></i> <?php echo $viewButtonText; ?>
                                    </a>
                                    <?php if (!empty($item['download_link'])) : ?>
                                        <a href="<?php echo htmlspecialchars($item['download_link']); ?>" class="btn btn-secondary w-full sm:w-auto justify-center" download aria-label="<?php echo $downloadButtonText . ' for ' . htmlspecialchars($item['title']); ?>">
                                            <i class="fas fa-download" aria-hidden="true"></i> <?php echo $downloadButtonText; ?>
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-secondary w-full sm:w-auto justify-center opacity-50 cursor-not-allowed" disabled aria-label="Download not available">
                                            <i class="fas fa-download" aria-hidden="true"></i> Download
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
    <div id="lightboxModal" class="lightbox-modal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="lightbox-content">
            <div id="lightboxSpinner" class="lightbox-spinner" role="status" aria-label="Loading image"></div>
            <img id="lightboxImage" src="" alt="Internship Gallery Image">
            <button id="lightboxPrev" class="lightbox-nav-btn prev" aria-label="Previous image">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button id="lightboxNext" class="lightbox-nav-btn next" aria-label="Next image">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
            <button id="lightboxClose" class="lightbox-close-btn" aria-label="Close image gallery">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>
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
        const lightboxModal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        const lightboxClose = document.getElementById('lightboxClose');
        const lightboxSpinner = document.getElementById('lightboxSpinner');
        let currentImages = [];
        let currentIndex = 0;
        let touchStartX = 0;
        let touchEndX = 0;
        const swipeThreshold = 50;

        function showSpinner() {
            lightboxSpinner.classList.add('show');
            lightboxImage.style.opacity = '0';
        }

        function hideSpinner() {
            lightboxSpinner.classList.remove('show');
            lightboxImage.style.opacity = '1';
        }

        function preloadImage(url) {
            if (!url) return;
            const img = new Image();
            img.src = url;
        }

        function updateLightboxImage() {
            if (currentImages.length === 0) {
                console.warn('No images provided for lightbox.');
                closeLightbox();
                return;
            }
            showSpinner();
            const imgToLoad = new Image();
            imgToLoad.src = currentImages[currentIndex];
            imgToLoad.onload = () => {
                lightboxImage.src = currentImages[currentIndex];
                lightboxImage.alt = `Gallery image ${currentIndex + 1} of ${currentImages.length}`;
                hideSpinner();
                if (currentIndex < currentImages.length - 1) {
                    preloadImage(currentImages[currentIndex + 1]);
                }
                if (currentIndex > 0) {
                    preloadImage(currentImages[currentIndex - 1]);
                }
            };
            imgToLoad.onerror = () => {
                console.error(`Failed to load image: ${currentImages[currentIndex]}`);
                lightboxImage.src = 'https://via.placeholder.com/600x400/FF0000/FFFFFF?text=Image+Load+Error';
                lightboxImage.alt = 'Image failed to load';
                hideSpinner();
            };
            lightboxPrev.style.display = currentImages.length > 1 ? 'flex' : 'none';
            lightboxNext.style.display = currentImages.length > 1 ? 'flex' : 'none';
            lightboxPrev.disabled = currentIndex === 0;
            lightboxNext.disabled = currentIndex === currentImages.length - 1;
        }

        function openLightbox(images, startIndex = 0) {
            if (images && images.length > 0) { // Ensure images array is not empty
                currentImages = images;
                currentIndex = startIndex;
                updateLightboxImage();
                lightboxModal.classList.add('active');
                lightboxModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                document.body.style.touchAction = 'none';
                lightboxClose.focus();
            } else {
                console.warn('Attempted to open lightbox with no images.');
            }
        }

        function closeLightbox() {
            lightboxModal.classList.remove('active');
            lightboxModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            document.body.style.touchAction = '';
            hideSpinner();
            lightboxImage.src = '';
            lightboxImage.alt = 'Internship Gallery Image';
        }
        lightboxPrev.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateLightboxImage();
            }
        });
        lightboxNext.addEventListener('click', () => {
            if (currentIndex < currentImages.length - 1) {
                currentIndex++;
                updateLightboxImage();
            }
        });
        lightboxClose.addEventListener('click', closeLightbox);
        lightboxModal.addEventListener('click', (e) => {
            if (e.target === lightboxModal) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', (e) => {
            if (lightboxModal.classList.contains('active')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    lightboxPrev.click();
                } else if (e.key === 'ArrowRight') {
                    lightboxNext.click();
                }
            }
        });
        lightboxModal.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, {
            passive: true
        });
        lightboxModal.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            const deltaX = touchEndX - touchStartX;
            if (Math.abs(deltaX) > swipeThreshold) {
                if (deltaX > 0) {
                    lightboxPrev.click();
                } else {
                    lightboxNext.click();
                }
            }
        }, {
            passive: true
        });
    </script>
</body>

</html>