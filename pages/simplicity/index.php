<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Flow</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #0a0a0a;
            color: #fff;
            overflow-x: hidden;
            line-height: 1.6;
        }

        .gl-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        section {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 0 10vw;
            position: relative;
        }

        .hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            padding: 0 10vw;
        }

        .hero h1 {
            font-size: clamp(3rem, 15vw, 12rem);
            font-weight: 700;
            line-height: 0.9;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeUp 1s ease forwards;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.5rem);
            max-width: 600px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s ease 0.3s forwards;
        }

        .section-content {
            max-width: 1200px;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease, transform 1s ease;
        }

        .section-content.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 4rem);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .section-text {
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            max-width: 600px;
            color: rgba(255,255,255,0.8);
        }

        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
            opacity: 0;
            animation: fadeIn 1s ease 1s forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 0.5;
            }
        }

        .nav {
            position: fixed;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
        }

        .nav-dot {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            margin: 1rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-dot.active {
            background: #fff;
            transform: scale(1.5);
        }
    </style>
</head>
<body>
    <div class="gl-container"></div>
    
    <div class="content">
        <section class="hero">
            <h1>DIGITAL<br>FLOW</h1>
            <p>Explore the intersection of design and technology through an immersive journey.</p>
            <div class="scroll-indicator">↓</div>
        </section>

        <section>
            <div class="section-content">
                <h2 class="section-title">Innovation</h2>
                <p class="section-text">Where creativity meets technological advancement, we push the boundaries of digital expression.</p>
            </div>
        </section>

        <section>
            <div class="section-content">
                <h2 class="section-title">Experience</h2>
                <p class="section-text">Immerse yourself in a world where digital interactions become meaningful connections.</p>
            </div>
        </section>

        <section>
            <div class="section-content">
                <h2 class="section-title">Future</h2>
                <p class="section-text">Shaping tomorrow's digital landscape through innovative design and thoughtful interaction.</p>
            </div>
        </section>
    </div>

    <nav class="nav">
        <div class="nav-dot active"></div>
        <div class="nav-dot"></div>
        <div class="nav-dot"></div>
        <div class="nav-dot"></div>
    </nav>

    <script>
        // Three.js setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.querySelector('.gl-container').appendChild(renderer.domElement);

        // Custom shader for the background animation
        const vertexShader = `
            varying vec2 vUv;
            void main() {
                vUv = uv;
                gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
            }
        `;

        const fragmentShader = `
            uniform float time;
            uniform vec2 resolution;
            varying vec2 vUv;

            void main() {
                vec2 position = vUv * 2.0 - 1.0;
                position.x *= resolution.x / resolution.y;
                
                float d = length(position) * 2.0;
                vec3 color = vec3(0.0);
                
                float t = time * 0.5;
                
                // Create moving waves
                float wave1 = sin(d * 5.0 - t) * 0.5 + 0.5;
                float wave2 = sin(d * 7.0 + t * 1.2) * 0.5 + 0.5;
                float wave3 = sin(d * 3.0 + t * 0.8) * 0.5 + 0.5;
                
                color += vec3(0.1, 0.3, 0.5) * wave1;
                color += vec3(0.2, 0.5, 0.8) * wave2;
                color += vec3(0.3, 0.7, 1.0) * wave3;
                
                // Add gradient
                color *= smoothstep(2.0, 0.5, d);
                
                gl_FragColor = vec4(color, 1.0);
            }
        `;

        // Create shader material
        const material = new THREE.ShaderMaterial({
            uniforms: {
                time: { value: 0 },
                resolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) }
            },
            vertexShader,
            fragmentShader
        });

        // Create plane geometry that fills the screen
        const geometry = new THREE.PlaneGeometry(2, 2);
        const mesh = new THREE.Mesh(geometry, material);
        scene.add(mesh);

        // Animation
        const clock = new THREE.Clock();
        const animate = () => {
            requestAnimationFrame(animate);
            material.uniforms.time.value = clock.getElapsedTime();
            renderer.render(scene, camera);
        };

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
            material.uniforms.resolution.value.set(window.innerWidth, window.innerHeight);
        });

        // Scroll handling
        const sections = document.querySelectorAll('section');
        const navDots = document.querySelectorAll('.nav-dot');

        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Update nav dots
                    const index = Array.from(sections).indexOf(entry.target);
                    navDots.forEach(dot => dot.classList.remove('active'));
                    navDots[index].classList.add('active');

                    // Animate section content
                    const content = entry.target.querySelector('.section-content');
                    if (content) content.classList.add('visible');
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));

        // Nav dot click handling
        navDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                sections[index].scrollIntoView({ behavior: 'smooth' });
            });
        });

        animate();
    </script>
</body>
</html>
