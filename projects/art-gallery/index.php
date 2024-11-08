<!DOCTYPE html>
<html>
<head>
    <title>3D Art Gallery</title>
    <style>
        body { margin: 0; }
        canvas { display: block; }
        #instructions {
            position: fixed;
            top: 10px;
            left: 10px;
            color: white;
            background: rgba(0,0,0,0.7);
            padding: 10px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div id="instructions">
        Use UP/DOWN arrows to move forward/backward<br>
        Use mouse to look around
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Lighting setup
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.3);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffaa88, 0.6);
        directionalLight.position.set(-1, 2, 1).normalize();
        scene.add(directionalLight);

        // Tunnel parameters
        const tunnelLength = 50;
        const tunnelWidth = 6;
        const tunnelHeight = 4;

        // Wall texture
        const wallTexture = new THREE.TextureLoader().load('wall.jpeg');
        const wallMaterial = new THREE.MeshPhongMaterial({ map: wallTexture });

        // Floor and ceiling textures
        const floorTexture = new THREE.TextureLoader().load('marble.jpeg'); // Add actual texture URL
        const ceilingTexture = new THREE.TextureLoader().load('marble.jpeg'); // Add actual texture URL
        floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping;
        ceilingTexture.wrapS = ceilingTexture.wrapT = THREE.RepeatWrapping;
        floorTexture.repeat.set(10, 10);
        ceilingTexture.repeat.set(10, 10);

        const floorMaterial = new THREE.MeshPhongMaterial({ map: floorTexture });
        const ceilingMaterial = new THREE.MeshPhongMaterial({ map: ceilingTexture });

        // Create tunnel walls
        const wallGeometry = new THREE.BoxGeometry(0.1, tunnelHeight, tunnelLength);
        const leftWall = new THREE.Mesh(wallGeometry, wallMaterial);
        leftWall.position.x = -tunnelWidth / 2;
        scene.add(leftWall);

        const rightWall = new THREE.Mesh(wallGeometry, wallMaterial);
        rightWall.position.x = tunnelWidth / 2;
        scene.add(rightWall);

        // Create floor and ceiling
        const floorGeometry = new THREE.BoxGeometry(tunnelWidth, 0.1, tunnelLength);
        const floor = new THREE.Mesh(floorGeometry, floorMaterial);
        floor.position.y = -tunnelHeight / 2;
        scene.add(floor);

        const ceiling = new THREE.Mesh(floorGeometry, ceilingMaterial);
        ceiling.position.y = tunnelHeight / 2;
        scene.add(ceiling);

        // Art frames setup
        const frameCount = 10;
        const spacing = tunnelLength / frameCount;
        const textureLoader = new THREE.TextureLoader();

        for (let i = 0; i < frameCount; i++) {
            const leftFrame = createArtFrame(i + 1);
            leftFrame.position.set(-tunnelWidth / 2 + 0.2, 0, -tunnelLength / 2 + i * spacing + spacing / 2);
            leftFrame.rotation.y = Math.PI / 2;
            scene.add(leftFrame);

            const rightFrame = createArtFrame(i + frameCount + 1);
            rightFrame.position.set(tunnelWidth / 2 - 0.2, 0, -tunnelLength / 2 + i * spacing + spacing / 2);
            rightFrame.rotation.y = -Math.PI / 2;
            scene.add(rightFrame);
        }

        function createArtFrame(imageNumber) {
            const frameGeometry = new THREE.BoxGeometry(0.1, 2, 1.5);
            const frameMaterial = new THREE.MeshPhongMaterial({ color: 0x8b4513 });
            const frame = new THREE.Mesh(frameGeometry, frameMaterial);

            const texture = textureLoader.load(`${imageNumber}.jpeg`);
            const artGeometry = new THREE.PlaneGeometry(1.3, 1.8);
            const artMaterial = new THREE.MeshBasicMaterial({ map: texture });
            const artwork = new THREE.Mesh(artGeometry, artMaterial);
            
            artwork.position.z = 0.80; // Slightly in front of the frame
            frame.add(artwork);

            return frame;
        }

        // Camera setup
        camera.position.z = tunnelLength / 2 - 2;
        camera.position.y = 0;

        // Control handlers
        let mouseX = 0;
        const mouseSensitivity = 0.002;
        let targetRotation = 0;

        const moveSpeed = 0.1;
        const keys = {};

        document.addEventListener('mousemove', (event) => {
            mouseX = event.clientX - window.innerWidth / 2;
            targetRotation = mouseX * mouseSensitivity;
            camera.rotation.y = Math.max(Math.min(targetRotation, Math.PI / 2), -Math.PI / 2);
        });

        document.addEventListener('keydown', (event) => {
            keys[event.key] = true;
        });

        document.addEventListener('keyup', (event) => {
            keys[event.key] = false;
        });

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        function animate() {
            requestAnimationFrame(animate);
            if (keys['ArrowUp']) camera.position.z -= moveSpeed;
            if (keys['ArrowDown']) camera.position.z += moveSpeed;
            camera.position.z = Math.max(Math.min(camera.position.z, tunnelLength / 2 - 2), -tunnelLength / 2 + 2);
            renderer.render(scene, camera);
        }

        animate();
    </script>
</body>
</html>
