<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Symmetry Processor</title>
    <link rel="stylesheet" href="/css/root.css">
    <link rel="stylesheet" href="/css/projects/image-symmetry-processor.css">
</head>
<body>
    <?php include '../../nav.php'; ?>
    <div class="container">
        <h1>Image Symmetry Processor</h1>
        <div id="drop-zone">
            <label id="upload-label" for="upload">Drag & Drop or Click to Upload</label>
            <input type="file" id="upload" accept="image/*">
        </div>
        <div id="image-container">
            <canvas id="canvas"></canvas>
        </div>
        <button id="download-btn" style="display:none;">Download Processed Image</button>
    </div>
    <script src="/js/projects/image-symmetry-processor.js" defer></script>
</body>
</html>
