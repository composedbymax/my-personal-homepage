let scene, camera, renderer, audioContext, analyser, dataArray, source;
let mesh, historyLength = 256, fftSize = 1024;
let stftData = [];
let isPlaying = false;
let sensitivity = 1;
let micStream = null;
let isMicEnabled = false;
let time = 0;
let animationFrameId = null;

init();

function init() {
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 30, 150);
    camera.lookAt(scene.position);

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setClearColor(0x000000);
    document.getElementById('visualizer').appendChild(renderer.domElement);

    // Initialize audio context when needed to avoid autoplay restrictions
    document.addEventListener('click', initAudioContext, { once: true });

    const geometry = new THREE.BufferGeometry();
    const vertices = new Float32Array(historyLength * (fftSize / 2) * 3);
    const colors = new Float32Array(historyLength * (fftSize / 2) * 3);
    geometry.setAttribute('position', new THREE.BufferAttribute(vertices, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: 1.5,
        vertexColors: true,
        blending: THREE.AdditiveBlending,
        transparent: true,
        opacity: 0.6,
        sizeAttenuation: true
    });

    mesh = new THREE.Points(geometry, material);
    scene.add(mesh);

    const ambientLight = new THREE.AmbientLight(0xffffff, 0.2);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
    directionalLight.position.set(0, 1, 1);
    scene.add(directionalLight);

    window.addEventListener('resize', onWindowResize, false);
    document.getElementById('upload').addEventListener('change', handleFileUpload, false);
    document.getElementById('playPause').addEventListener('click', togglePlayPause, false);
    document.getElementById('sensitivity').addEventListener('input', handleSensitivityChange, false);
    document.getElementById('toggleMic').addEventListener('click', toggleMicrophone, false);

    // Initialize stftData array
    for (let i = 0; i < historyLength; i++) {
        stftData.push(new Uint8Array(fftSize / 2).fill(0));
    }

    animate();
}

function initAudioContext() {
    if (!audioContext) {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        analyser = audioContext.createAnalyser();
        analyser.fftSize = fftSize;
        analyser.smoothingTimeConstant = 0.8;
        dataArray = new Uint8Array(analyser.frequencyBinCount);
    }
}

async function toggleMicrophone() {
    initAudioContext();
    const micButton = document.getElementById('toggleMic');

    if (!isMicEnabled) {
        try {
            if (source) {
                source.disconnect();
                isPlaying = false;
            }

            micStream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: false,
                    noiseSuppression: false,
                    autoGainControl: false
                }
            });

            source = audioContext.createMediaStreamSource(micStream);
            source.connect(analyser);
            isMicEnabled = true;
            isPlaying = true;
            micButton.classList.add('active');
            micButton.textContent = 'Disable Mic';

            // Resume audio context if suspended
            if (audioContext.state === 'suspended') {
                await audioContext.resume();
            }
        } catch (err) {
            console.error('Error accessing microphone:', err);
            alert('Unable to access microphone. Please ensure you have granted microphone permissions.');
        }
    } else {
        if (micStream) {
            micStream.getTracks().forEach(track => track.stop());
            source.disconnect();
            micStream = null;
        }
        isMicEnabled = false;
        isPlaying = false;
        micButton.classList.remove('active');
        micButton.textContent = 'Enable Mic';
    }
}

function handleFileUpload(event) {
    initAudioContext();
    const file = event.target.files[0];
    if (file) {
        if (isMicEnabled) {
            toggleMicrophone();
        }

        const fileReader = new FileReader();
        fileReader.onload = function (e) {
            audioContext.decodeAudioData(e.target.result, function (buffer) {
                if (source) {
                    source.disconnect();
                }
                source = audioContext.createBufferSource();
                source.buffer = buffer;
                source.connect(analyser);
                analyser.connect(audioContext.destination);
                isPlaying = true;
                source.start();
            });
        };
        fileReader.readAsArrayBuffer(file);
    }
}

function togglePlayPause() {
    if (isMicEnabled) return;

    if (audioContext && audioContext.state === 'suspended') {
        audioContext.resume();
    }
    if (isPlaying) {
        source.stop();
        isPlaying = false;
    } else if (source && source.buffer) {
        source = audioContext.createBufferSource();
        source.buffer = source.buffer;
        source.connect(analyser);
        analyser.connect(audioContext.destination);
        source.start();
        isPlaying = true;
    }
}

function handleSensitivityChange(event) {
    sensitivity = parseFloat(event.target.value);
}

function updateSTFT() {
    if (!analyser || (!isPlaying && !isMicEnabled)) return;

    analyser.getByteFrequencyData(dataArray);

    // Update history
    stftData.unshift(new Uint8Array(dataArray));
    stftData.pop();

    const positions = mesh.geometry.attributes.position.array;
    const colors = mesh.geometry.attributes.color.array;
    let index = 0;
    let colorIndex = 0;

    for (let t = 0; t < stftData.length; t++) {
        for (let f = 0; f < dataArray.length; f++) {
            const value = stftData[t][f];
            const normalizedValue = value / 255;

            const x = (t - historyLength / 2) * 0.5;
            const y = (f * 0.3 - 50);
            const z = (value / 128) * sensitivity;

            positions[index++] = x;
            positions[index++] = y;
            positions[index++] = z;

            // Enhanced color mapping for better visibility
            const hue = (f / dataArray.length) * 0.3 + 0.6;
            const saturation = 0.8;
            const lightness = 0.3 + normalizedValue * 0.7; // Increased contrast

            const h = hue * 6;
            const s = saturation;
            const l = lightness;
            const c = (1 - Math.abs(2 * l - 1)) * s;
            const x1 = c * (1 - Math.abs((h % 2) - 1));
            const m = l - c / 2;
            let r, g, b;

            if (h < 1) { r = c; g = x1; b = 0; }
            else if (h < 2) { r = x1; g = c; b = 0; }
            else if (h < 3) { r = 0; g = c; b = x1; }
            else if (h < 4) { r = 0; g = x1; b = c; }
            else if (h < 5) { r = x1; g = 0; b = c; }
            else { r = c; g = 0; b = x1; }

            colors[colorIndex++] = r + m;
            colors[colorIndex++] = g + m;
            colors[colorIndex++] = b + m;
        }
    }

    mesh.geometry.attributes.position.needsUpdate = true;
    mesh.geometry.attributes.color.needsUpdate = true;
}

function animate() {
    animationFrameId = requestAnimationFrame(animate);
    updateSTFT();
    renderer.render(scene, camera);
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}