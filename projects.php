<?php include_once 'header.php'; ?>
<?php
$pageTitle = "Kishan Raj - My Projects";
$pageDescription = "Explore a collection of web development projects by Kishan Raj, including personal portfolios, AI applications, e-commerce platforms, and more.";
if (!isset($userProfilePicturePath)) {
    $userProfilePicturePath = "https://via.placeholder.com/256/00d4ff/00b8e6?text=KR";
}
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
    </style>
</head>

<body class="bg-bg-primary text-text-primary">
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
                    <div class="project-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 2) * 100; ?>">
                        <div class="p-4">
                            <div class="mb-4 overflow-hidden rounded-lg">
                                <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank" rel="noopener noreferrer">
                                    <img src="<?php echo $project['images'][0]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> main preview" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-105">
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-6">
                                <img src="<?php echo $project['images'][1]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> thumbnail 1" class="w-full h-32 object-cover rounded-md border border-[var(--border)]">
                                <img src="<?php echo $project['images'][2]; ?>" alt="<?php echo htmlspecialchars($project['title']); ?> thumbnail 2" class="w-full h-32 object-cover rounded-md border border-[var(--border)]">
                            </div>
                        </div>
                        <div class="px-6 pb-6 flex-grow flex flex-col">
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
                <p class="text-[var(--text-tertiary)] text-sm">
                    <i class="fab fa-php text-indigo-600"></i> PHP
                    <i class="fab fa-html5 text-orange-600"></i> HTML
                    <i class="fa-brands fa-css3-alt text-blue-500"></i> CSS
                    <img src="https://tailwindcss.com/favicons/favicon-32x32.png" alt="Tailwind" class="w-4 h-4 inline"> Tailwind
                    <i class="fab fa-js-square text-yellow-500"></i> JS
                    <i class="fab fa-google text-red-500"></i> Google
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
    </script>
</body>

</html>