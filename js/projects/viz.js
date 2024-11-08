const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const visualizerType = document.getElementById('visualizerType');
const aspectRatioSelect = document.getElementById('aspectRatio');
const canvasContainer = document.getElementById('canvasContainer');

let backgroundImage = null;
let centerImage = null;
let audio = null;
let audioContext = null;
let analyser = null;
let mediaRecorder = null;
let chunks = [];
let animationFrame = null;
let centerImageSize = 200;
let particles = [];
let wavePhase = 0;
let time = 0;

function updateCanvasSize() {
    const ratio = aspectRatioSelect.value;
    canvasContainer.className = `canvas-container aspect-${ratio.replace(':', '-')}`;
    
    if (ratio === '16:9') {
        canvas.width = 1280;
        canvas.height = 720;
    } else {
        canvas.width = 720;
        canvas.height = 1280;
    }

    // Update centerImageSize based on canvas dimensions
    centerImageSize = Math.min(canvas.width, canvas.height) * 0.25;

    // Reinitialize particles if they exist
    if (particles.length > 0) {
        initParticles();
    }

    // Redraw if we're already animating
    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
        draw();
    }
}

class Particle {
    constructor(centerX, centerY) {
        this.reset(centerX, centerY);
        this.lifespan = Math.random() * 0.5 + 0.5;
        this.age = 0;
    }

    reset(centerX, centerY) {
        const angle = Math.random() * Math.PI * 2;
        const distance = Math.random() * (Math.min(canvas.width, canvas.height) * 0.05);

        this.x = centerX + Math.cos(angle) * distance;
        this.y = centerY + Math.sin(angle) * distance;
        this.angle = angle;
        this.distance = distance;
        this.baseSize = Math.random() * 6 + 3;
        this.size = this.baseSize;
        this.speed = Math.random() * 3 + 1.5;
        this.hue = Math.random() * 60 - 30;
        this.age = 0;
        this.oscillationSpeed = Math.random() * 0.1 + 0.05;
        this.oscillationAmplitude = Math.random() * (Math.min(canvas.width, canvas.height) * 0.03);
    }

    update(volume, centerX, centerY, time) {
        this.age += 0.016;
        if (this.age >= this.lifespan) {
            this.reset(centerX, centerY);
        }

        const lifePercent = this.age / this.lifespan;
        const fadeIn = Math.min(this.age * 4, 1);
        const fadeOut = 1 - Math.max(0, (lifePercent - 0.7) / 0.3);
        this.opacity = fadeIn * fadeOut;

        this.distance += this.speed * (volume / 128);
        const oscillation = Math.sin(time * this.oscillationSpeed) * this.oscillationAmplitude;
        this.size = this.baseSize * (1 + volume / 256) * (1 - lifePercent * 0.5);

        const modifiedAngle = this.angle + oscillation * 0.02;
        this.x = centerX + Math.cos(modifiedAngle) * (this.distance + oscillation);
        this.y = centerY + Math.sin(modifiedAngle) * (this.distance + oscillation);
    }

    draw(ctx, baseHue) {
        const gradient = ctx.createRadialGradient(
            this.x, this.y, 0,
            this.x, this.y, this.size
        );
        const color1 = chroma.hsl((baseHue + this.hue) % 360, 1, 0.7).alpha(0.8 * this.opacity);
        const color2 = chroma.hsl((baseHue + this.hue) % 360, 1, 0.5).alpha(0);

        gradient.addColorStop(0, color1.css());
        gradient.addColorStop(1, color2.css());

        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
    }
}

function initParticles() {
    particles = [];
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const particleCount = Math.floor((canvas.width * canvas.height) / 4000); // Adjust particle count based on canvas size
    for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle(centerX, centerY));
    }
}

document.getElementById('backgroundInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => backgroundImage = img;
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
});

document.getElementById('centerImageInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => centerImage = img;
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
});

document.getElementById('audioInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = (event) => {
        audio = new Audio();
        audio.src = event.target.result;
        setupAudioContext();
    };
    reader.readAsDataURL(file);
});

function setupAudioContext() {
    audioContext = new (window.AudioContext || window.webkitAudioContext)();
    analyser = audioContext.createAnalyser();
    const source = audioContext.createMediaElementSource(audio);
    source.connect(analyser);
    analyser.connect(audioContext.destination);
    analyser.fftSize = 512;
    initParticles();
}

function drawCircularVisualizer(ctx, dataArray, bufferLength, centerX, centerY, average, time) {
    // Constants for visualization
    const waves = 8; // Reduced number of main waves
    const baseRadius = Math.min(canvas.width, canvas.height) * 0.15;
    const layers = 3; // Number of nested wave layers

    // Process frequency data into waves
    const waveData = [];
    const chunkSize = Math.floor(bufferLength / waves);
    for (let i = 0; i < waves; i++) {
        const start = i * chunkSize;
        const chunk = dataArray.slice(start, start + chunkSize);
        waveData[i] = chunk.reduce((a, b) => a + b) / chunkSize;
    }

    // Draw multiple layers for depth effect
    for (let layer = 0; layer < layers; layer++) {
        ctx.beginPath();

        const layerOffset = layer * 30;
        const waveAmplitude = 150 - (layer * 20); // Decreasing amplitude for outer layers

        // Draw complete wave circle
        for (let angle = 0; angle <= Math.PI * 2; angle += 0.02) {
            // Find which wave segment we're in
            const waveIndex = Math.floor((angle / (Math.PI * 2)) * waves) % waves;
            const nextWaveIndex = (waveIndex + 1) % waves;

            // Interpolate between wave segments for smoothness
            const waveProgress = (angle / (Math.PI * 2) * waves) % 1;
            const currentIntensity = waveData[waveIndex] / 255;
            const nextIntensity = waveData[nextWaveIndex] / 255;
            const intensity = currentIntensity * (1 - waveProgress) + nextIntensity * waveProgress;

            // Create dynamic wave shape
            const waveEffect =
                Math.sin(angle * waves + time * 2) * 0.3 +
                Math.sin(angle * (waves / 2) - time) * 0.2;

            // Calculate radius with smooth transitions
            const radius = baseRadius + layerOffset +
                (waveAmplitude * intensity * (1 + waveEffect));

            const x = centerX + Math.cos(angle) * radius;
            const y = centerY + Math.sin(angle) * radius;

            if (angle === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }

        ctx.closePath();

        // Create dynamic color gradient
        const alpha = 0.8 - (layer * 0.2);
        const hue = (average + layer * 30 + time * 20) % 360;

        const gradient = ctx.createRadialGradient(
            centerX, centerY, baseRadius + layerOffset,
            centerX, centerY, baseRadius + layerOffset + waveAmplitude
        );

        gradient.addColorStop(0, `hsla(${hue}, 90%, 50%, ${alpha})`);
        gradient.addColorStop(0.5, `hsla(${hue + 30}, 95%, 60%, ${alpha * 0.7})`);
        gradient.addColorStop(1, `hsla(${hue}, 90%, 50%, 0)`);

        // Apply styles with strong glow
        ctx.strokeStyle = gradient;
        ctx.lineWidth = 4 - layer; // Thicker lines for more prominence
        ctx.stroke();

        // Add intense glow effect
        ctx.shadowBlur = 20;
        ctx.shadowColor = `hsla(${hue}, 90%, 50%, 0.6)`;
        ctx.stroke();
        ctx.shadowBlur = 0;

        // Add subtle fill for depth
        ctx.fillStyle = `hsla(${hue}, 80%, 50%, 0.1)`;
        ctx.fill();
    }

    // Add pulsing center ring
    ctx.beginPath();
    const pulseSize = baseRadius * (0.9 + (average / 255) * 0.2);
    ctx.arc(centerX, centerY, pulseSize, 0, Math.PI * 2);
    ctx.strokeStyle = `hsla(${average % 360}, 90%, 50%, 0.4)`;
    ctx.lineWidth = 3;
    ctx.stroke();
}


function drawBars(ctx, dataArray, bufferLength, centerX, centerY, average, time) {
    const barWidth = (canvas.width / bufferLength) * 2.5;
    const maxHeight = canvas.height * 0.8;

    // Mirror the bars on both sides
    for (let side = 0; side < 2; side++) {
        let x = side === 0 ? centerX : centerX - barWidth;

        for (let i = 0; i < bufferLength / 2; i++) {
            const value = dataArray[i];
            const percent = value / 256;
            const barHeight = percent * maxHeight;

            // Calculate dynamic color
            const hue = (average + i * 2 + time * 30) % 360;
            const brightness = 40 + percent * 30;

            // Create gradient for each bar
            const gradient = ctx.createLinearGradient(
                x, canvas.height,
                x, canvas.height - barHeight
            );
            gradient.addColorStop(0, `hsla(${hue}, 70%, ${brightness}%, 0.9)`);
            gradient.addColorStop(1, `hsla(${hue + 30}, 70%, ${brightness + 20}%, 0.7)`);

            ctx.fillStyle = gradient;

            // Add glow effect
            ctx.shadowBlur = 15;
            ctx.shadowColor = `hsla(${hue}, 70%, 50%, 0.5)`;

            // Draw bar with rounded corners
            const barX = side === 0 ? x : x - barWidth;
            ctx.beginPath();
            ctx.roundRect(
                barX,
                canvas.height - barHeight,
                barWidth,
                barHeight,
                [barWidth / 2, barWidth / 2, 0, 0]
            );
            ctx.fill();

            ctx.shadowBlur = 0;

            x += side === 0 ? barWidth + 1 : -(barWidth + 1);
        }
    }
}

function drawWaveform(ctx, dataArray, bufferLength, time) {
    const centerY = canvas.height / 2;
    const amplitude = canvas.height / 3;
    wavePhase += 0.05;

    // Draw multiple waves with different properties
    for (let wave = 0; wave < 3; wave++) {
        ctx.beginPath();
        ctx.lineWidth = 4 - wave;

        const hue = (wave * 60 + time * 30) % 360;
        const gradient = ctx.createLinearGradient(0, centerY - amplitude, 0, centerY + amplitude);
        gradient.addColorStop(0, `hsla(${hue}, 70%, 60%, 0.8)`);
        gradient.addColorStop(0.5, `hsla(${hue + 30}, 70%, 60%, 0.6)`);
        gradient.addColorStop(1, `hsla(${hue}, 70%, 60%, 0.8)`);
        ctx.strokeStyle = gradient;

        const frequency = (wave + 1) * 2;
        const phaseOffset = wave * Math.PI / 3;

        for (let i = 0; i < bufferLength; i++) {
            const x = (canvas.width * i) / bufferLength;
            const normalizedValue = dataArray[i] / 128.0 - 1;

            const waveFactor = Math.sin(frequency * (i / bufferLength) * Math.PI * 2 + wavePhase + phaseOffset) +
                Math.sin(frequency * 1.5 * (i / bufferLength) * Math.PI * 2 - wavePhase * 0.5);

            const y = centerY + normalizedValue * amplitude * waveFactor * 0.5;

            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }

        // Add glow effect
        ctx.shadowBlur = 15;
        ctx.shadowColor = `hsla(${hue}, 70%, 60%, 0.5)`;
        ctx.stroke();
        ctx.shadowBlur = 0;
    }
}

function drawParticles(ctx, average, time) {
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;

    ctx.globalCompositeOperation = 'screen';
    particles.forEach(particle => {
        particle.update(average, centerX, centerY, time);
        particle.draw(ctx, (average + time * 30) % 360);
    });
    ctx.globalCompositeOperation = 'source-over';
}

function applyBackgroundEffects(ctx, average) {
    const glow = average / 255;
    ctx.fillStyle = `rgba(0, 0, 0, ${0.2 + glow * 0.1})`;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    const gradient = ctx.createRadialGradient(
        canvas.width / 2, canvas.height / 2, 0,
        canvas.width / 2, canvas.height / 2, Math.max(canvas.width, canvas.height) * 0.7
    );
    gradient.addColorStop(0, 'rgba(0,0,0,0)');
    gradient.addColorStop(1, `rgba(0,0,0,${0.4 + glow * 0.2})`);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}

function draw() {
    if (!backgroundImage || !centerImage || !audio) return;

    time += 0.016;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw background
    ctx.globalAlpha = 0.8;
    ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);
    ctx.globalAlpha = 1.0;

    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    analyser.getByteFrequencyData(dataArray);

    const average = dataArray.reduce((a, b) => a + b) / bufferLength;
    const scale = 1 + (average / 512);

    applyBackgroundEffects(ctx, average);

    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;

    // Draw visualizer effects FIRST (before center image)
    switch (visualizerType.value) {
        case 'circular':
            drawCircularVisualizer(ctx, dataArray, bufferLength, centerX, centerY, average, time);
            break;
        case 'bars':
            drawBars(ctx, dataArray, bufferLength, centerX, centerY, average, time);
            break;
        case 'particles':
            drawParticles(ctx, average, time);
            break;
        case 'waveform':
            drawWaveform(ctx, dataArray, bufferLength, time);
            break;
    }

    // Draw center image
    const size = centerImageSize * scale;
    ctx.save();
    ctx.beginPath();
    ctx.arc(centerX, centerY, size / 2, 0, Math.PI * 2);
    ctx.clip();
    ctx.drawImage(
        centerImage,
        centerX - size / 2,
        centerY - size / 2,
        size,
        size
    );
    ctx.restore();

    animationFrame = requestAnimationFrame(draw);
}

// File loading handlers

// Event listeners for controls
startBtn.addEventListener('click', async () => {
    if (!backgroundImage || !centerImage || !audio) {
        alert('Please load all files first');
        return;
    }

    const stream = canvas.captureStream();
    const audioStream = audio.captureStream();
    const tracks = [...stream.getTracks(), ...audioStream.getTracks()];
    const combinedStream = new MediaStream(tracks);

    mediaRecorder = new MediaRecorder(combinedStream, {
        mimeType: 'video/webm'
    });

    mediaRecorder.ondataavailable = (e) => chunks.push(e.data);
    mediaRecorder.onstop = () => {
        const blob = new Blob(chunks, { type: 'video/webm' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'visualization.webm';
        a.click();
        chunks = [];
    };

    audio.currentTime = 0;
    await audio.play();
    mediaRecorder.start();
    time = 0;
    draw();

    startBtn.disabled = true;
    stopBtn.disabled = false;
});

stopBtn.addEventListener('click', () => {
    audio.pause();
    mediaRecorder.stop();
    cancelAnimationFrame(animationFrame);
    startBtn.disabled = false;
    stopBtn.disabled = true;
});

// Initialize with default size
updateCanvasSize();

// Add aspect ratio change handler
aspectRatioSelect.addEventListener('change', updateCanvasSize);