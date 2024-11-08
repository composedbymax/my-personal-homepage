<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inteliuz | STFT</title>
    <link rel="stylesheet" href="/css/projects/stft.css">
</head>
<body>
    <?php include '../../nav.php'; ?>
    <div id="container">
        <div id="visualizer"></div>
        <div id="controls">
            <input type="file" id="upload" accept="audio/*" />
            <button id="toggleMic">Enable Mic</button>
            <button id="playPause">Play/Pause</button>
            <label for="sensitivity">Sensitivity</label>
            <input type="range" id="sensitivity" min="1" max="5" step="0.1" value="1">
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="/js/projects/stft.js" defer></script>
    <?php include '../../foot.php'; ?>
</body>
</html>