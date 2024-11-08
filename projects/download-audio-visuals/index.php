<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inteliuz | Vizzy</title>
    <link rel="stylesheet" href="/css/projects/viz.css">
</head>
<body class="main-body">
    <?php include '../../nav.php'; ?>
    <div class="container">
        <div class="grid">
            <div class="upload-section">
                <div class="section-title">VIZ - Inteliuz</div>
                <select id="aspectRatio" class="dropdown">
                    <option value="16:9">16:9 (Landscape)</option>
                    <option value="9:16">9:16 (Portrait)</option>
                </select>
                <div class="custom-file-input">
                    <label for="backgroundInput" class="file-label">Upload Background Image</label>
                    <input type="file" id="backgroundInput" accept="image/*" class="input-field" />
                </div>
                <div class="custom-file-input">
                    <label for="centerImageInput" class="file-label">Upload Center Image</label>
                    <input type="file" id="centerImageInput" accept="image/*" class="input-field" />
                </div>
                <div class="custom-file-input">
                    <label for="audioInput" class="file-label">Upload Audio File</label>
                    <input type="file" id="audioInput" accept="audio/*" class="input-field" />
                </div>

            </div>
            <div class="controls-section">
                <select id="visualizerType" class="dropdown">
                    <option value="circular">Circular Waves</option>
                    <option value="bars">Frequency Bars</option>
                    <option value="particles">Particles</option>
                    <option value="waveform">Waveform</option>
                </select>
                <div class="button-group">
                    <button id="startBtn" class="btn start-btn">Start Recording</button>
                    <button id="stopBtn" class="btn stop-btn" disabled>Stop Recording</button>
                </div>
            </div>
        </div>
    </div>
    <div id="canvasContainer" class="canvas-container aspect-16-9">
        <canvas id="canvas" class="canvas"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.4.2/chroma.min.js"></script>
    <script src="/js/projects/viz.js" defer></script>
</body>
</html>