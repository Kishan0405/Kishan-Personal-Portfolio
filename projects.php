<?php
include_once 'header.php';

// Set page-specific variables to override defaults from header.php
$pageTitle = "Kishan Raj - My Projects";
$pageDescription = "Explore a collection of web development projects by Kishan Raj, including personal portfolios, AI applications, e-commerce platforms, and more.";

// Project data array
$projects = [
    [
        'title' => 'My New Personal Portfolio',
        'description' => 'My latest personal website involves the use of PHP, HTML, CSS, JS and Tailwind. The project showcases all my projects, internships and contact. All the contents of the website are the same as the details listed in LinkedIn profile. This project is a great example of my skills in web development and design using my creative logic skills and Vibe-Coding (Mainly Generative AI based coding).',
        'link' => '/',
        'view_code' => 'https://github.com/Kishan0405/Kishan-Personal-Portfolio',
        'images' => [
            'includes/project_image/image_13.png',
            'includes/project_image/image_14.png',
            'includes/project_image/image_15.png'
        ],
        'tags' => ['PHP', 'Tailwind CSS', 'JavaScript', 'Responsive Design']
    ],
    [
        'title' => 'QuizzletMaster Search',
        'description' => 'Gareeb logon ka search engine QuizzletMaster Search will be a dedicated, high-performance search engine built for the QuizzletMaster platform. This engine service enhances user experience by allowing quick and efficient searching of quizzes and topics, demonstrating my skills in building specialized, functional tools. Search Clicks are limited for 500 Clicks is much than enough for a single user.',
        'link' => 'http://search.quizzletmaster.in/',
        'view_code' => '',
        'images' => [
            'includes/project_image/image_10.png',
            'includes/project_image/image_11.png',
            'includes/project_image/image_12.png'
        ],
        'tags' => ['Gareeb logon ka search engine', 'Tailwind', 'PHP', 'HTML/JS', 'Python']
    ],
    [
        'title' => 'QuizzletMaster',
        'description' => 'QuizzletMaster is an innovative online quiz platform proudly developed in India by Kishan Raj. QuizzletMaster always provide a seamless, engaging and interactive quiz experience for users worldwide, whether you are here to learn, challenge others or create your own educational content.',
        'link' => 'http://quizzletmaster.in/',
        'view_code' => '',
        'images' => [
            'includes/project_image/image_7.png',
            'includes/project_image/image_8.png',
            'includes/project_image/image_9.png'
        ],
        'tags' => ['PHP', 'JavaScript', 'Bootstrap', 'Tailwind', 'Education', 'Free Quizzes', 'Free Quiz Creation', 'Online Learning']
    ],
    [
        'title' => 'Special BOX UI E-Commerce Platform',
        'description' => 'A limited functional e-commerce platform designed using PHP, HTML, CSS and JS. This project shows the importance of UI/UX design in e-commerce,focusing on user experience and product presentation. It includes features like product listing, categories, cart, wishlist and buy feature. Admin can add, delete or customize the orders etc. This project is hosted on a free hosting solution. The project is incomplete and later on in upcomming future I will make it the part of QuizzletMaster platform.',
        'link' => 'https://specialboxuionline.wuaze.com/specialboxuionline/home.php',
        'view_code' => '',
        'images' => [
            'includes/project_image/image_4.png',
            'includes/project_image/image_5.png',
            'includes/project_image/image_6.png'
        ],
        'tags' => ['E-commerce', 'PHP', 'HTML', 'HTML/CSS']
    ],
    [
        'title' => 'My Previous First designed Personal Website',
        'description' => 'One of my first major personal website, this website served as a foundational learning experience using HTML, CSS, JS and PHP in addition with using Generative AI as coding helper. This personal website helped me to understand the web technologies and free hosting solutions.',
        'link' => 'https://specialboxuionline.wuaze.com/?i=1',
        'view_code' => '',
        'images' => [
            'includes/project_image/image_1.png',
            'includes/project_image/image_2.png',
            'includes/project_image/image_3.png'
        ],
        'tags' => ['Portfolio', 'PHP', 'HTML', 'CSS', 'Beginner']
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

<body>
    <main class="main-content-wrapper flex-1 px-2 py-8 mt-0">
        <section class="max-w-6xl mx-auto mb-16 mt-0 text-center" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold gradient-text mb-4 font-space">My Projects</h1>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto">
                A collection of my work as projects, from early learning experiences to AI based applications.
            </p>
            <p class="text-[var(--text-secondary)] text-lg max-w-4xl mx-auto">
                Each project represents a step and experience in vibe-coding helps as a web developer.
            </p>
        </section>

        <section class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($projects as $index => $project) : ?>
                    <div class="card_projects glass-hover flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">

                        <div class="p-4 md:p-6">
                            <div class="mb-4 overflow-hidden rounded-lg">
                                <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank" rel="noopener noreferrer">
                                    <img src="<?php echo $project['images'][0]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> main preview" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-105">
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2 md:mb-4"> <img src="<?php echo $project['images'][1]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> thumbnail 1" class="w-full h-32 object-cover rounded-md border border-[var(--border)]">
                                <img src="<?php echo $project['images'][2]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> thumbnail 2" class="w-full h-32 object-cover rounded-md border border-[var(--border)]">
                            </div>
                        </div>

                        <div class="px-4 md:px-6 pb-6 flex-grow flex flex-col">
                            <h3 class="text-2xl font-bold mb-2 font-space"><?php echo htmlspecialchars($project['title']); ?></h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach ($project['tags'] as $tag) : ?>
                                    <span class="px-3 py-1 bg-[var(--accent)]/10 text-[var(--accent)] text-xs font-semibold rounded-full border border-[var(--accent)]/20"><?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6 flex-grow">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>
                            <div class="mt-auto flex flex-col sm:flex-row gap-4">
                                <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-full sm:w-auto justify-center">
                                    <i class="fas fa-external-link-alt"></i> Live Demo
                                </a>
                                <?php if (!empty($project['view_code'])) : ?>
                                    <a href="<?php echo htmlspecialchars($project['view_code']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary w-full sm:w-auto justify-center">
                                        <i class="fab fa-github"></i> View Code
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-secondary w-full sm:w-auto justify-center opacity-50 cursor-not-allowed" onclick="event.preventDefault(); alert('Code repository is private.');">
                                        <i class="fab fa-github"></i> View Code
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>

</html>

<?php include_once 'footer.php'; ?>