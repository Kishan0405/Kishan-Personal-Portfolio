<?php
include_once 'header.php';

$pageTitle = "Kishan Raj - Internship Experience";
$pageDescription = "A collection of my internship reports, presentations, and photo galleries showcasing my work and experiences during my internship period.";

$internshipItems = [
    [
        'title' => 'Spatiotemporal Analysis of Urban Expansion Using NDBI from Satellite Imagery',
        'description' => 'Attended training sessions and worked on a project focused on analyzing urban expansion using NDBI. Gained hands-on experience with tools like GEE and QGIS and prepared a detailed report based on the findings.',
        'link' => 'includes/internship_docs/Kishan Raj_P4.pdf',
        'download_link' => 'includes/internship_docs/Kishan Raj_P4.pdf',
        'images' => [
            'includes/internship_docs/no_image.gif',
        ],
        'tags' => ['QGIS', 'GEE', 'Urban Expansion', 'NDBI', '2025', 'PDF']
    ],
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
    <link rel="stylesheet" href="css/internships.css">
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
    <main class="main-content-wrapper flex-1 px-4 py-8 mt-0">
        <section class="max-w-6xl mx-auto mb-16 mt-0 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold gradient-text mb-4 font-space">Internship Journey</h1>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto">
                A collection of my internship reports, presentations and visual insights.
            </p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php foreach ($internshipItems as $index => $item) : ?>
                        <?php
                        $mainImageSrc = !empty($item['images']) ? htmlspecialchars($item['images'][0]) : 'https://via.placeholder.com/600x400/CCCCCC/888888?text=No+Image';
                        $mainImageAlt = !empty($item['images']) ? htmlspecialchars($item['title'] . ' main preview') : 'No image available for ' . htmlspecialchars($item['title']);
                        ?>

                        <div class="card glass-hover flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">

                            <div class="relative overflow-hidden rounded-t-lg group">
                                <img src="<?php echo $mainImageSrc; ?>" alt="<?php echo $mainImageAlt; ?>" class="w-full h-64 object-cover object-center transition-transform duration-500 ease-in-out group-hover:scale-105">

                                <?php if (!empty($item['images'])) : ?>
                                    <?php
                                    $imagesJson = htmlspecialchars(json_encode($item['images']), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <?php if (count($item['images']) > 1) : ?>
                                        <button class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-lg font-bold"
                                            onclick="openLightbox(<?php echo $imagesJson; ?>, 0)"
                                            aria-label="View gallery of <?php echo htmlspecialchars($item['title']); ?>">
                                            <i class="fas fa-images mr-2" aria-hidden="true"></i> View Gallery (<?php echo count($item['images']); ?>)
                                        </button>
                                    <?php else : ?>
                                        <button class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-lg font-bold"
                                            onclick="openLightbox(<?php echo $imagesJson; ?>, 0)"
                                            aria-label="Enlarge image of <?php echo htmlspecialchars($item['title']); ?>">
                                            <i class="fas fa-expand mr-2" aria-hidden="true"></i> Enlarge Image
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
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
    </main>

    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <div class="lightbox-content" onclick="event.stopPropagation()">
            <div id="lightboxSpinner" class="lightbox-spinner"></div>
            <img id="lightboxImage" src="" alt="Enlarged internship image">
            <button id="lightboxClose" class="lightbox-close-btn" onclick="closeLightbox()" aria-label="Close lightbox">&times;</button>
            <button id="lightboxPrev" class="lightbox-nav-btn prev" onclick="prevImage()" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
            <button id="lightboxNext" class="lightbox-nav-btn next" onclick="nextImage()" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
            <div id="lightboxCaption" class="lightbox-caption"></div>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            disable: window.innerWidth < 768
        });

        let currentLightboxImages = [];
        let currentLightboxIndex = 0;
        const lightboxModal = document.getElementById('lightboxModal');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxSpinner = document.getElementById('lightboxSpinner');
        const lightboxCaption = document.getElementById('lightboxCaption');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');

        function openLightbox(images, index) {
            currentLightboxImages = images;
            currentLightboxIndex = index;
            lightboxModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            showImage(currentLightboxIndex);
            document.addEventListener('keydown', handleLightboxKeydown);
        }

        function closeLightbox() {
            lightboxModal.classList.remove('active');
            document.body.style.overflow = '';
            document.removeEventListener('keydown', handleLightboxKeydown);
        }

        function showImage(index) {
            lightboxSpinner.classList.add('show');
            lightboxImage.style.opacity = 0;

            const img = new Image();
            img.onload = () => {
                lightboxImage.src = img.src;
                lightboxSpinner.classList.remove('show');
                lightboxImage.style.opacity = 1;
            };
            img.onerror = () => {
                lightboxImage.src = 'https://via.placeholder.com/600x400/FF4757/FFFFFF?text=Image+Not+Found';
                lightboxSpinner.classList.remove('show');
                lightboxImage.style.opacity = 1;
            };
            img.src = currentLightboxImages[index];

            lightboxCaption.textContent = `Image ${index + 1} of ${currentLightboxImages.length}`;
            lightboxPrev.disabled = index === 0;
            lightboxNext.disabled = index === currentLightboxImages.length - 1;
        }

        function nextImage() {
            if (currentLightboxIndex < currentLightboxImages.length - 1) {
                currentLightboxIndex++;
                showImage(currentLightboxIndex);
            }
        }

        function prevImage() {
            if (currentLightboxIndex > 0) {
                currentLightboxIndex--;
                showImage(currentLightboxIndex);
            }
        }

        function handleLightboxKeydown(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            }
        }

        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    </script>

</body>

</html>

<?php
include_once 'footer.php';
?>