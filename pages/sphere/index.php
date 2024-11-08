<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I'm Bored</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            overflow-x: hidden;
            background: #000;
            color: white;
            cursor: none;
        }

        #custom-cursor {
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            mix-blend-mode: difference;
            z-index: 9999;
            transition: width 0.3s, height 0.3s;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        section {
            min-height: 100vh;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            opacity: 0;
            transition: opacity 0.8s ease;
        }

        section.active {
            opacity: 1;
        }

        .title {
            font-size: 8vw;
            font-weight: 900;
            letter-spacing: -0.05em;
            text-align: center;
            mix-blend-mode: difference;
            line-height: 0.9;
            margin-bottom: 2rem;
            opacity: 1;
        }

        .subtitle {
            font-size: 1.5rem;
            text-align: center;
            max-width: 600px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease-out 0.2s;
        }

        .links {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease-out 0.4s;
        }

        .link {
            color: white;
            text-decoration: none;
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .link:hover {
            background: white;
            color: black;
            transform: scale(1.1);
        }

        section.active .subtitle,
        section.active .links,
        section.active .card-container,
        section.active .contact-form {
            opacity: 1;
            transform: translateY(0);
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            width: 100%;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease-out 0.4s;
            text-align: center;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-content {
            margin-bottom: 1rem;
        }

        .card .link {
            align-self: center;
            margin-top: auto;
            padding: 1rem 2rem;
            border: 2px solid white;
            border-radius: 30px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .card:hover .link {
            background: white;
            color: black;
            transform: scale(1.1);
        }


        .card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            max-width: 500px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease-out 0.4s;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            background: rgba(255, 255, 255, 0.2);
            outline: none;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 12vw;
            }
            section {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div id="custom-cursor"></div>
    <div class="content">
        <section>
            <h1 class="title">Is This Ugly?</h1>
            <p class="subtitle">idek tbh</p>
            <div class="links">
                <a href="#tools" class="link">Tools</a>
                <a href="#contact" class="link">Contact</a> 
            </div>
        </section>

        <section>
            <h2 class="title">Portfolio</h2>
            <div class="card-container">
                <div class="card">
                    <div class="card-content">
                        <h3>Digital Art</h3>
                        <p>Exploring new mediums through digital expression</p>
                    </div>
                    <a href="https://google.com" class="link" target="_blank">View Project</a>
                </div>
                
                
                <div class="card">
                    <div class="card-content">
                        <h3>Digital Art</h3>
                        <p>Exploring new mediums through digital expression</p>
                    </div>
                    <a href="https://google.com" class="link" target="_blank">View Project</a>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3>Digital Art</h3>
                        <p>Exploring new mediums through digital expression</p>
                    </div>
                    <a href="https://google.com" class="link" target="_blank">View Project</a>
                </div>
            </div>
        </section>

        <section id="tools">
            <h2 class="title">Projects</h2>
            <div class="card-container">
                <div class="card">
                    <div class="card-content">
                        <h3>Digital Art</h3>
                        <p>Exploring new mediums through digital expression</p>
                    </div>
                    <a href="https://google.com" class="link" target="_blank">View Project</a>
                </div>
                
                <div class="card">
                    <h3>Project Beta</h3>
                    <p>Pushing the boundaries of web animation</p>
                    <a href="https://google.com" class="link" target="_blank">Learn More</a>
                </div>
                <div class="card">
                    <h3>Project Gamma</h3>
                    <p>Experimental design concepts</p>
                    <a href="https://google.com" class="link" target="_blank">Learn More</a>
                </div>
            </div>
        </section>

        <!-- Contact Section with ID -->
        <section id="contact">
            <h2 class="title">Contact</h2>
            <form class="contact-form">
                <input type="text" placeholder="Name" required>
                <input type="email" placeholder="Email" required>
                <textarea rows="4" placeholder="Message" required></textarea>
                <a href="https://google.com" class="link" target="_blank">Send Message</a>
            </form>
        </section>
    </div>

    <script>
        // Three.js setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setClearColor(0x000000);
        document.body.insertBefore(renderer.domElement, document.body.firstChild);

        // Particle sphere creation with unique behaviors
        function createParticleSphere(radius, count, color, uniqueParams) {
            const geometry = new THREE.BufferGeometry();
            const positions = new Float32Array(count * 3);
            const colors = new Float32Array(count * 3);
            const scales = new Float32Array(count);
            const originalPositions = new Float32Array(count * 3);

            for (let i = 0; i < count; i++) {
                const phi = Math.acos(1 - 2 * (i / count));
                const theta = Math.PI * (1 + Math.sqrt(5)) * i;

                const x = radius * Math.sin(phi) * Math.cos(theta);
                const y = radius * Math.sin(phi) * Math.sin(theta);
                const z = radius * Math.cos(phi);

                positions[i * 3] = x;
                positions[i * 3 + 1] = y;
                positions[i * 3 + 2] = z;

                // Store original positions for animations
                originalPositions[i * 3] = x;
                originalPositions[i * 3 + 1] = y;
                originalPositions[i * 3 + 2] = z;

                colors[i * 3] = color.r;
                colors[i * 3 + 1] = color.g;
                colors[i * 3 + 2] = color.b;

                scales[i] = Math.random() * 0.5 + 0.5;
            }

            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
            geometry.setAttribute('scale', new THREE.BufferAttribute(scales, 1));

            const material = new THREE.PointsMaterial({
                size: uniqueParams.particleSize || 0.15,
                vertexColors: true,
                blending: THREE.AdditiveBlending,
                transparent: true,
                opacity: 0.8
            });

            const sphere = new THREE.Points(geometry, material);
            sphere.userData = {
                originalPositions: originalPositions,
                uniqueParams: uniqueParams
            };

            return sphere;
        }

        // Create spheres with unique parameters
        const spheres = [
            createParticleSphere(20, 12000, { r: 1, g: 0.5, b: 0.5 }, {
                particleSize: 0.15,
                rotationSpeed: 0.0003,
                waveSpeed: 0.00015,
                waveIntensity: 0.15,
                animationType: 'spiral'
            }),
            createParticleSphere(20, 12000, { r: 0.5, g: 1, b: 0.5 }, {
                particleSize: 0.12,
                rotationSpeed: 0.0004,
                waveSpeed: 0.0002,
                waveIntensity: 0.2,
                animationType: 'pulse'
            }),
            createParticleSphere(20, 12000, { r: 0.5, g: 0.5, b: 1 }, {
                particleSize: 0.13,
                rotationSpeed: 0.0005,
                waveSpeed: 0.00025,
                waveIntensity: 0.25,
                animationType: 'wave'
            }),
            createParticleSphere(20, 12000, { r: 1, g: 1, b: 0.5 }, {
                particleSize: 0.14,
                rotationSpeed: 0.0006,
                waveSpeed: 0.0003,
                waveIntensity: 0.3,
                animationType: 'vortex'
            })
        ];

        // Position spheres
        spheres.forEach((sphere, i) => {
            sphere.position.z = i * 100;
            scene.add(sphere);
        });

        // Camera setup with improved smoothing
        camera.position.z = 30;
        let currentCameraZ = 30;
        const cameraSmoothing = 0.08;

        // Scroll handling with improved transitions
        const sections = document.querySelectorAll('section');
        let currentSection = 0;
        let targetZ = 30;

        // Initialize first section
        sections[0].classList.add('active');

        // Create Intersection Observer with optimized thresholds
        const observerOptions = {
            root: null,
            rootMargin: '-5%',
            threshold: [0, 0.3, 0.7, 1]
        };

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.intersectionRatio > 0.3) {
                    entry.target.classList.add('active');
                    const sectionIndex = Array.from(sections).indexOf(entry.target);
                    currentSection = sectionIndex;
                    targetZ = 30 + (currentSection * 100);
                } else {
                    entry.target.classList.remove('active');
                }
            });
        }, observerOptions);

        sections.forEach(section => sectionObserver.observe(section));

        // Animation loop with improved performance
        let lastTime = 0;
        const lerp = (start, end, factor) => start + (end - start) * factor;

        function animate(currentTime = 0) {
            requestAnimationFrame(animate);

            const deltaTime = Math.min(currentTime - lastTime, 32); // Cap delta time to avoid large jumps
            lastTime = currentTime;

            // Smooth camera movement with exponential interpolation
            currentCameraZ = lerp(currentCameraZ, targetZ, cameraSmoothing);
            camera.position.z = currentCameraZ;

            // Update spheres with unique animations
            spheres.forEach((sphere, index) => {
                const { uniqueParams, originalPositions } = sphere.userData;
                const positions = sphere.geometry.attributes.position.array;
                const time = currentTime * uniqueParams.waveSpeed;

                // Apply unique rotation speeds
                sphere.rotation.x += uniqueParams.rotationSpeed * deltaTime;
                sphere.rotation.y += uniqueParams.rotationSpeed * deltaTime;

                // Apply different animation patterns based on type
                for(let i = 0; i < positions.length; i += 3) {
                    const x = originalPositions[i];
                    const y = originalPositions[i + 1];
                    const z = originalPositions[i + 2];
                    
                    switch(uniqueParams.animationType) {
                        case 'spiral':
                            positions[i] = x + Math.sin(time + y * 0.1) * uniqueParams.waveIntensity;
                            positions[i + 1] = y + Math.cos(time + x * 0.1) * uniqueParams.waveIntensity;
                            positions[i + 2] = z + Math.sin(time * 1.5) * uniqueParams.waveIntensity;
                            break;
                        case 'pulse':
                            const pulseScale = 1 + Math.sin(time * 2) * 0.1;
                            positions[i] = x * pulseScale;
                            positions[i + 1] = y * pulseScale;
                            positions[i + 2] = z * pulseScale;
                            break;
                        case 'wave':
                            const wave = Math.sin(time + (x * 0.5 + z * 0.5)) * uniqueParams.waveIntensity;
                            positions[i + 1] = y + wave;
                            break;
                        case 'vortex':
                            const angle = Math.atan2(z, x) + time;
                            const radius = Math.sqrt(x * x + z * z);
                            positions[i] = Math.cos(angle) * radius;
                            positions[i + 2] = Math.sin(angle) * radius;
                            break;
                    }
                }
                
                sphere.geometry.attributes.position.needsUpdate = true;

                // Smooth opacity transitions
                const targetOpacity = Math.max(0.2, 1 - Math.abs(index - currentSection) * 0.5);
                sphere.material.opacity = lerp(sphere.material.opacity, targetOpacity, 0.1);
            });

            renderer.render(scene, camera);
        }

        // Optimized window resize handler with debouncing
        let resizeTimeout;
        window.addEventListener('resize', () => {
            if (resizeTimeout) clearTimeout(resizeTimeout);
            
            resizeTimeout = setTimeout(() => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            }, 10);
        });

        // Improved cursor with smoother transitions
        const cursor = document.getElementById('custom-cursor');
        let cursorX = 0, cursorY = 0;
        const cursorSmoothing = 0.15;

        document.addEventListener('mousemove', (e) => {
            cursorX = e.clientX - 10;
            cursorY = e.clientY - 10;
        });

        function updateCursor() {
            const currentX = parseFloat(cursor.style.transform.split('(')[1]) || cursorX;
            const currentY = parseFloat(cursor.style.transform.split(',')[1]) || cursorY;
            
            const x = lerp(currentX, cursorX, cursorSmoothing);
            const y = lerp(currentY, cursorY, cursorSmoothing);
            
            cursor.style.transform = `translate(${x}px, ${y}px)`;
            requestAnimationFrame(updateCursor);
        }

        document.addEventListener('mouseover', (e) => {
            const size = e.target.classList.contains('link') || e.target.classList.contains('card') ? '40px' : '20px';
            cursor.style.width = size;
            cursor.style.height = size;
        });

        // Start animations
        animate();
        updateCursor();

    
    </script>
</body>
</html>