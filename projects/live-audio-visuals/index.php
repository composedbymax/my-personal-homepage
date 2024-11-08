<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inteliuz | LiveIt</title>
  <link rel="stylesheet" href="/css/projects/liveit.css">
</head>
<body>
  <?php include '../../nav.php'; ?>
  <div id="visualization-container"></div>
  <div class="controls">
    <button id="toggle-mic">Enable Mic</button>
    <button id="fullscreen">Fullscreen</button>
  </div>
  <div class="visualizer-list" id="visualizer-list">
    <div class="visualizer-option active" data-viz="water">Water Waves</div>
    <div class="visualizer-option" data-viz="particles">Particle Field</div>
    <div class="visualizer-option" data-viz="galaxy">Spiral Galaxy</div>
    <div class="visualizer-option" data-viz="cubes">Dancing Cubes</div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>

  <script src="/js/projects/liveit.js" defer></script>
</body>
</html>