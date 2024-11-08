<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inteliuz | Pic Tool</title>
    <link rel="stylesheet" href="/css/projects/pictool.css">
    <link rel="stylesheet" href="/css/root.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
</head>
<body>
    <?php include '../../nav.php'; ?>

    <h1>Pic Editor</h1>
    <input type="file" id="baseImage" accept="image/*">
    <input type="file" id="otherImages" accept="image/*" multiple>
    <label id="brightness-label">Brightness Adjustment: <input type="range" id="brightness" min="0.5" max="2" step="0.1" value="1"></label>
    
    <!-- New input fields for width and height -->
    <label>Output Width: <input type="number" id="outputWidth" placeholder="e.g., 1080"></label>
    <label>Output Height: <input type="number" id="outputHeight" placeholder="e.g., 1920"></label>
    
    <button id="process" class="button">Process</button>
    <button id="downloadAll" class="button">Download All as ZIP</button>
    <div id="loading">Processing images, please wait...</div>
    <div id="output"></div>

    <script src="/js/projects/pictool.js" defer></script>
</body>
</html>
