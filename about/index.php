<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Max's About Me</title>
    <link rel="stylesheet" href="/css/root.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .about-me {
            display: grid;
            grid-template-columns: 1fr;
            grid-gap: 40px;
            justify-items: center;
            align-items: center;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        @media (min-width: 768px) {
            .about-me {
                grid-template-columns: 1fr 1fr;
                padding: 80px 40px;
                grid-gap: 80px;
            }
        }

        .about-me-content {
            background-color: var(--card-dark);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .about-me-content h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--neon-blue);
            text-shadow: 0 0 20px var(--neon-blue);
        }

        @media (min-width: 768px) {
            .about-me-content h1 {
                font-size: 48px;
            }
        }

        .about-me-content p {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .stats {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 30px;
        }

        .stat {
            background-color: var(--secondary-dark);
            border-radius: var(--border-radius);
            padding: 15px 20px;
            text-align: center;
            margin: 5px;
            transition: var(--transition-smooth);
            flex: 1 1 100px;
        }

        .stat:hover {
            background-color: var(--neon-blue-glow);
            color: var(--primary-dark);
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--neon-blue);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .skills {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            grid-gap: 20px;
            width: 100%;
        }

        .skill {
            background-color: var(--secondary-dark);
            border-radius: var(--border-radius);
            padding: 20px;
            text-align: center;
            transition: var(--transition-smooth);
            border: 2px solid transparent;
        }

        .skill:hover {
            background-color: var(--neon-blue-glow);
            color: var(--primary-dark);
            border-color: var(--neon-blue);
        }

        .skill-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--neon-blue);
        }

        .skill-description {
            font-size: 16px;
            color: var(--text-secondary);
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideInFromBottom {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
    </style>

</head>
<body>
    <?php include '../nav.php'; ?>
    <div class="about-me">
        <div class="about-me-content">
            <h1>About Me</h1>
            <p>Hi, I'm Max, and I've been coding for <span style="color: var(--neon-blue); font-weight: bold;">3 months</span>! I'm passionate about creating innovative and visually stunning projects using Next.js, PHP, JavaScript, TypeScript, and Tailwind CSS.</p>
            <div class="stats">
                <div class="stat">
                    <div class="stat-value">23</div>
                    <div class="stat-label">Age</div>
                </div>
                <div class="stat">
                    <div class="stat-value">3</div>
                    <div class="stat-label">Months Coding</div>
                </div>
                <div class="stat">
                    <div class="stat-value">PHP, JS, TS</div>
                    <div class="stat-label">Languages</div>
                </div>
            </div>
        </div>
        <div class="skills">
            <div class="skill">
                <div class="skill-name">Next.js</div>
                <div class="skill-description">React framework for building fast and scalable web applications</div>
            </div>
            <div class="skill">
                <div class="skill-name">PHP</div>
                <div class="skill-description">Powerful server-side language for web development</div>
            </div>
            <div class="skill">
                <div class="skill-name">JavaScript</div>
                <div class="skill-description">Versatile language for both client and server-side programming</div>
            </div>
            <div class="skill">
                <div class="skill-name">TypeScript</div>
                <div class="skill-description">Superset of JavaScript with static typing for better tooling and scalability</div>
            </div>
            <div class="skill">
                <div class="skill-name">Tailwind CSS</div>
                <div class="skill-description">Utility-first CSS framework for rapid UI development</div>
            </div>
        </div>
    </div>
</body>
</html>