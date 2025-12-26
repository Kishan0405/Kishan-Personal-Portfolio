<?php
include_once 'header.php';
if (!isset($pageTitle)) {
    $pageTitle = "Kishan Raj - Biotechnology Engineer & Web Developer";
}
if (!isset($pageDescription)) {
    $pageDescription = "Official personal portfolio of Kishan Raj, a Biotechnology student passionate about web development and AI based solutions.";
}
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
<body>
    <main class="main-content-wrapper flex-1 px-2 py-8 mt-0">
        <section class="relative max-w-6xl mx-auto mb-20 mt-0" data-aos="fade-up">
            <div class="hero-pattern"></div>
            <div class="glass rounded-3xl p-6 md:p-0 lg:p-12 relative overflow-hidden">
                <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-12">
                    <div class="relative group flex-shrink-0">
                        <div class="relative w-40 h-40 md:w-48 md:h-48 lg:w-64 lg:h-64">
                            <img src="includes/kishan_raj.png" alt="Kishan Raj" class="w-full h-full rounded-full object-cover border-4 border-[var(--accent)] shadow-xl">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[var(--accent)]/20 to-[var(--accent-secondary)]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        </div>
                    </div>
                    <div class="text-center lg:text-left flex-1 hero-content">
                        <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                            <span class="inline-block px-4 py-2 bg-[var(--accent)]/10 text-[var(--accent)] rounded-full text-sm font-medium border border-[var(--accent)]/20">
                                Available for Projects
                            </span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold gradient-text mb-4 font-space" data-aos="fade-up" data-aos-delay="300">
                            Kishan Raj
                        </h1>
                        <p class="text-lg md:text-xl lg:text-2xl text-[var(--text-secondary)] mb-4 typing-text" data-aos="fade-up" data-aos-delay="400">
                            BT & AI Enthusiastic | Web Developer
                        </p>
                        <p class="text-base lg:text-lg text-[var(--text-tertiary)] mb-8 max-w-2xl leading-relaxed mx-auto lg:mx-0" data-aos="fade-up" data-aos-delay="500">
                            3rd-year B.Tech student pursuing Biotechnology Engineering and passionate about creating optimized web (static & dynamic), AI-Service based web technology. My creative goal to bring changes in the biological sciences and technology.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start" data-aos="fade-up" data-aos-delay="600">
                            <a href="contact.php" class="btn btn-primary">
                                <i class="fas fa-user"></i> Contact Me
                            </a>
                            <a href="#projects" class="btn btn-secondary">
                                <i class="fas fa-eye"></i> Overview of My Work
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="max-w-6xl mx-auto mb-20" data-aos="fade-up" data-aos-delay="100">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4 font-space">About Me</h2>
                <p class="text-[var(--text-secondary)] text-lg max-w-2xl mx-auto">
                    Let me introduce myself and my passion and my skills.
                </p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card glass-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-[var(--accent)] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold">My Story</h3>
                    </div>
                    <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                        I am currently a 3rd-year undergraduate student pursuing a Bachelor of Technology (B.Tech) in Biotechnology Engineering at N M A M Institute of Technology, Nitte. My topic of interest in the Web Development (Build Professional Web based Static and Dynamic UI/UX), Environmental Biotechnology and Plant Biotechnology so that I can contribute to build professional websites optimized for all devices using Generative Artificial Intelligence, advance in agriculture and environmental sustainability. I always learn and expand my knowledge and skills to projects in environmental biotechnology, bioinformatics and related field.
                    </p>
                    <p class="text-[var(--text-secondary)] leading-relaxed">
                        I am a person where I learn and have the ideas, knowledge and initiatives but don't know how to start with or begin with...
                    </p>
                </div>
                <div class="card glass-hover">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-[var(--accent-secondary)] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-heart text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-semibold">My Passion</h3>
                    </div>
                    <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                        Since I joined as a biotechnology student I am not from the computer background but when I learned about coding basics, I got interested in developing static and dynamic websites. As far as I develop websites mainly use HTML, CSS and JS and for dynamic I mainly choose PHP and rarely Python Programming. I know the basics and how to operate, logics of coding (mainly for fixing) and maintain professional and creative website (Free Tier and Paid Tier too). I always use Generative Artificial Intelligence (Mainly Gemini, Claude, Grok, ChatGPT, Perplexity, Google Developer Mode AI) to simply those tasks.
                    </p>
                    <p class="text-[var(--text-secondary)] leading-relaxed">
                        I am a vibe-coder and often spend to create a new exciting projects related to web development and maintenance. If I had a chance I always open to collaborate and look forward to innovate and grow in the field of bioinformatics, environmental related solutions and AI technology.
                    </p>
                </div>
            </div>
        </section>
        <section class="max-w-6xl mx-auto mb-20" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4 font-space">My Skills</h2>
                <p class="text-[var(--text-secondary)] text-lg max-w-2xl mx-auto">
                    Let me showcase my skills and expertise that I have developed over the years.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card glass-hover">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-secondary)] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-vial text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Technical Skills</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Biotechnology Lab (Overall)</span>
                                <span class="text-[var(--accent)] text-sm">70%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="70%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Analytical & Bioprocess Techniques</span>
                                <span class="text-[var(--accent)] text-sm">85%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Microbiology & Life science related</span>
                                <span class="text-[var(--accent)] text-sm">65%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="65%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Coding</span>
                                <span class="text-[var(--accent)] text-sm">60%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="60%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Data Analysis</span>
                                <span class="text-[var(--accent)] text-sm">85%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">AI Integration</span>
                                <span class="text-[var(--accent)] text-sm">90%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">MS Word & PowerPoint</span>
                                <span class="text-[var(--accent)] text-sm">95%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card glass-hover">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-secondary)] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-code text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Programming</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">HTML/CSS</span>
                                <span class="text-[var(--accent)] text-sm">40%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="40%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">JavaScript</span>
                                <span class="text-[var(--accent)] text-sm">25%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="25%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">PHP</span>
                                <span class="text-[var(--accent)] text-sm">40%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="40%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Python</span>
                                <span class="text-[var(--accent)] text-sm">30%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="30%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Vibe-Coder</span>
                                <span class="text-[var(--accent)] text-sm">90%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Free Hosting & Managing</span>
                                <span class="text-[var(--accent)] text-sm">99%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="99%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card glass-hover">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-secondary)] rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Soft Skills</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Leadership</span>
                                <span class="text-[var(--accent)] text-sm">90%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Problem Solving</span>
                                <span class="text-[var(--accent)] text-sm">85%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Eager to Learn</span>
                                <span class="text-[var(--accent)] text-sm">85%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Communication</span>
                                <span class="text-[var(--accent)] text-sm">40%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="40%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[var(--text-secondary)]">Adaptability</span>
                                <span class="text-[var(--accent)] text-sm">95%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-skill-width="95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="max-w-6xl mx-auto mb-20" data-aos="fade-up" data-aos-delay="300">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4 font-space">My Education</h2>
                <p class="text-[var(--text-secondary)] text-lg max-w-2xl mx-auto">
                    My academic journey over the years, showcasing my dedication to learning and growth.
                </p>
            </div>
            <div class="space-y-6">
                <div class="card glass-hover">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-[var(--accent)] to-[var(--accent-secondary)] rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-1">Bachelor of Technology (B.Tech)</h3>
                                <p class="text-[var(--accent)] font-medium">Biotechnology Engineering</p>
                                <p class="text-[var(--text-secondary)] text-sm">N M A M Institute of Technology, Nitte</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 mt-2 md:mt-0">
                            <span class="inline-block px-3 py-1 bg-[var(--accent)]/10 text-[var(--accent)] rounded-full text-sm border border-[var(--accent)]/20">
                                2023 - Present
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card glass-hover">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-[var(--accent-secondary)] to-[var(--accent)] rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-school text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-1">Pre-University Course (PUC)</h3>
                                <p class="text-[var(--accent-secondary)] font-medium">PCMB Stream</p>
                                <p class="text-[var(--text-secondary)] text-sm">S V H PU College Innanje, Udupi</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 mt-2 md:mt-0">
                            <span class="inline-block px-3 py-1 bg-[var(--accent-secondary)]/10 text-[var(--accent-secondary)] rounded-full text-sm border border-[var(--accent-secondary)]/20">
                                2021 - 2023
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card glass-hover">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-[var(--accent-secondary)] to-[var(--accent)] rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-certificate text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-1">Senior Secondary Leaving Certificate (SSLC)</h3>
                                <p class="text-[var(--accent-secondary)] font-medium">Secondary Education</p>
                                <p class="text-[var(--text-secondary)] text-sm">S V H High School, Innanje, Udupi</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 mt-2 md:mt-0">
                            <span class="inline-block px-3 py-1 bg-[var(--accent-secondary)]/10 text-[var(--accent-secondary)] rounded-full text-sm border border-[var(--accent-secondary)]/20">
                                2018 - 2021
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="projects" class="max-w-6xl mx-auto mb-20" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4 font-space">Featured Projects</h2>
                <p class="text-[var(--text-secondary)] text-lg max-w-2xl mx-auto">
                    Explore some of my recent work, showcasing diverse web development skills
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card card-animated-border glass-hover group">
                    <div class="relative overflow-hidden rounded-lg mb-4">
                        <div class="h-48 bg-gradient-to-br from-[var(--accent)]/20 to-[var(--accent-secondary)]/20 flex items-center justify-center">
                            <img src="includes/project_image/image_13.png" alt="My New Personal Portfolio" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">My New Personal Portfolio</h3>
                    <p class="text-[var(--text-secondary)] mb-4">The latest iteration of my personal website, built with a focus on modern design, performance, and responsiveness.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-[var(--accent)]/10 text-[var(--accent)] rounded text-xs border border-[var(--accent)]/20">PHP</span>
                        <span class="px-2 py-1 bg-[var(--accent)]/10 text-[var(--accent)] rounded text-xs border border-[var(--accent)]/20">Tailwind CSS</span>
                        <span class="px-2 py-1 bg-[var(--accent)]/10 text-[var(--accent)] rounded text-xs border border-[var(--accent)]/20">JavaScript</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="index.php" class="btn btn-primary text-sm py-2 px-4" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt text-xs"></i> View Project
                        </a>
                        <a href="https://github.com/Kishan0405/Kishan-Personal-Portfolio"
                            class="btn btn-secondary text-sm py-2 px-4 inline-flex items-center gap-2"
                            target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-github text-xs"></i> Code
                        </a>
                    </div>
                </div>
                <div class="card card-animated-border glass-hover group">
                    <div class="relative overflow-hidden rounded-lg mb-4">
                        <div class="h-48 bg-gradient-to-br from-[var(--accent-secondary)]/20 to-[var(--accent)]/20 flex items-center justify-center">
                            <img src="includes/project_image/image_10.png" alt="QuizzletMaster Search" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">QuizzletMaster Search</h3>
                    <p class="text-[var(--text-secondary)] mb-4">A dedicated, high-performance search engine for the QuizzletMaster platform, enhancing user experience with quick quiz and topic searching.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-[var(--accent-secondary)]/10 text-[var(--accent-secondary)] rounded text-xs border border-[var(--accent-secondary)]/20">Tailwind</span>
                        <span class="px-2 py-1 bg-[var(--accent-secondary)]/10 text-[var(--accent-secondary)] rounded text-xs border border-[var(--accent-secondary)]/20">PHP</span>
                        <span class="px-2 py-1 bg-[var(--accent-secondary)]/10 text-[var(--accent-secondary)] rounded text-xs border border-[var(--accent-secondary)]/20">Python</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="http://search.quizzletmaster.in/" class="btn btn-primary text-sm py-2 px-4" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt text-xs"></i> View Project
                        </a>
                        <a href="#" class="btn btn-secondary text-sm py-2 px-4 opacity-50 cursor-not-allowed" onclick="event.preventDefault(); alert('Code repository is private.');">
                            <i class="fab fa-github text-xs"></i> Code
                        </a>
                    </div>
                </div>
                <div class="card card-animated-border glass-hover group">
                    <div class="relative overflow-hidden rounded-lg mb-4">
                        <div class="h-48 bg-gradient-to-br from-[var(--success)]/20 to-[var(--accent)]/20 flex items-center justify-center">
                            <img src="includes/project_image/image_7.png" alt="QuizzletMaster" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">QuizzletMaster</h3>
                    <p class="text-[var(--text-secondary)] mb-4">An innovative online quiz platform developed in India, offering a seamless and engaging interactive quiz experience for users worldwide.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-[var(--success)]/10 text-[var(--success)] rounded text-xs border border-[var(--success)]/20">PHP</span>
                        <span class="px-2 py-1 bg-[var(--success)]/10 text-[var(--success)] rounded text-xs border border-[var(--success)]/20">JavaScript</span>
                        <span class="px-2 py-1 bg-[var(--success)]/10 text-[var(--success)] rounded text-xs border border-[var(--success)]/20">Education</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="http://quizzletmaster.in/" class="btn btn-primary text-sm py-2 px-4" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt text-xs"></i> View Project
                        </a>
                        <a href="#" class="btn btn-secondary text-sm py-2 px-4 opacity-50 cursor-not-allowed" onclick="event.preventDefault(); alert('Code repository is private.');">
                            <i class="fab fa-github text-xs"></i> Code
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        // AOS Initialization - Lightweight config
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            disable: window.innerWidth < 768 // Disable on mobile for performance
        });

        // Skill bars animation
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const skillBars = entry.target.querySelectorAll('.skill-progress');
                        skillBars.forEach(bar => {
                            bar.style.width = bar.dataset.skillWidth;
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            document.querySelectorAll('.card.glass-hover').forEach(card => {
                observer.observe(card);
            });
        });

        // Theme toggle (if needed, lightweight)
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
<?php include_once 'footer.php'; ?>