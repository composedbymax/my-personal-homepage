<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SVG Editor</title>
  <link rel="stylesheet" href="/css/projects/svg-editor.css">
</head>
<body>
  <?php include '../..//nav.php'; ?>

  <div class="container">
    <div class="header">
      <h1>Inteliuz | SVG Editor</h1>
      <div>
        <button onclick="deleteSelected()">Delete Selected</button>
        <button onclick="generateSVG()">Generate SVG</button>
        <button onclick="downloadSVG()">Download SVG</button>
      </div>
    </div>

    <div class="controls">
      <div class="control-group">
        <label for="shapeSelect">Shape:</label>
        <select id="shapeSelect">
          <option value="rect">Rectangle</option>
          <option value="circle">Circle</option>
          <option value="ellipse">Ellipse</option>
          <option value="line">Line</option>
          <option value="triangle">Triangle</option>
          <option value="star">Star</option>
        </select>
      </div>

      <div class="control-group">
        <label for="fillColor">Fill Color:</label>
        <input type="color" id="fillColor" value="#4444ff">
      </div>

      <div class="control-group">
        <label for="strokeColor">Stroke Color:</label>
        <input type="color" id="strokeColor" value="#000000">
      </div>

      <div class="control-group">
        <label for="strokeWidth">Stroke Width:</label>
        <input type="number" id="strokeWidth" value="2" min="0" max="20">
      </div>

      <div class="control-group">
        <label for="opacity">Opacity:</label>
        <input type="range" id="opacity" min="0" max="1" step="0.1" value="1">
      </div>
    </div>

    <div class="canvas-container">
      <div id="canvas"></div>
    </div>

    <textarea id="svgOutput" readonly placeholder="Your SVG code will appear here..."></textarea>
  </div>

  <?php include '../../foot.php'; ?>

  <script src="/js/projects/svg-editor.js" defer></script>
</body>
</html>