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
    <main class="main-content-wrapper flex-1 px-2 py-8 mt-0">
        <section class="relative max-w-6xl mx-auto mb-20 mt-0" data-aos="fade-up">
            <div class="hero-pattern"></div>
            <div class="glass rounded-3xl p-6 md:p-0 lg:p-12 relative overflow-hidden">
                <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-12">
                    <div class="relative group flex-shrink-0">
                        <div class="relative w-40 h-40 md:w-48 md:h-48 lg:w-64 lg:h-64">
                            <img src="includes/kishan_raj.png" alt="Kishan Raj" class="w-full h-full rounded-full object-cover border-4 border-[var(--accent)] shadow-2xl">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-[var(--accent)]/20 to-[var(--accent-secondary)]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8ZGFzaGJvYXJkLGNvZGUsd2Vic2l0ZXx8fHx8fDE3MjE2Nzc3Nzk&ixlib=rb-4.0.3&q=80&w=1080" alt="My New Personal Portfolio" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                        <a href="#" class="btn btn-secondary text-sm py-2 px-4 opacity-50 cursor-not-allowed" onclick="event.preventDefault(); alert('Code repository is private.');">
                            <i class="fab fa-github text-xs"></i> Code
                        </a>
                    </div>
                </div>
                <div class="card card-animated-border glass-hover group">
                    <div class="relative overflow-hidden rounded-lg mb-4">
                        <div class="h-48 bg-gradient-to-br from-[var(--accent-secondary)]/20 to-[var(--accent)]/20 flex items-center justify-center">
                            <img src="includes/project_image/image_10.png" alt="QuizzletMaster Search" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
                            <img src="includes/project_image/image_7.png" alt="QuizzletMaster" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
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
        <footer class="text-center py-8 border-t border-[var(--border)]">
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
        let currentTheme = localStorage.getItem('theme') || 'light';
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
        const animateSkillBars = () => {
            const skillBars = document.querySelectorAll('.skill-progress');
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