<!DOCTYPE html>
<html>
<head>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #000;
      min-height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }

    .art-container {
      position: absolute;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .art-overlay {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
      z-index: 10;
      pointer-events: none;
      opacity: 0;
      animation: fadeIn 2s ease-in-out forwards;
    }

    .art-overlay h1 {
      font-size: 4rem;
      font-weight: 200;
      letter-spacing: 1rem;
      margin-bottom: 1rem;
      text-transform: uppercase;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    svg {
      width: 100vw;
      height: 100vh;
      position: absolute;
    }

    .background-layer {
      filter: blur(2px);
    }
  </style>
</head>
<body>
  <div class="art-container">
    <!-- Background Layer -->
    <svg class="background-layer" viewBox="0 0 110 110" preserveAspectRatio="none">
      <!-- Abstract flowing background -->
      <defs>
        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" style="stop-color:#1a1a1a;stop-opacity:1">
            <animate attributeName="stop-color" 
                     values="#1a1a1a;#2c1a4d;#1a1a1a" 
                     dur="10s" 
                     repeatCount="indefinite"/>
          </stop>
          <stop offset="100%" style="stop-color:#4a1a42;stop-opacity:1">
            <animate attributeName="stop-color" 
                     values="#4a1a42;#1a4d4d;#4a1a42" 
                     dur="10s" 
                     repeatCount="indefinite"/>
          </stop>
        </linearGradient>
        <filter id="turbulence">
          <feTurbulence type="fractalNoise" baseFrequency="0.01 0.01" numOctaves="3" seed="1">
            <animate attributeName="baseFrequency" 
                     values="0.01 0.01;0.015 0.015;0.01 0.01" 
                     dur="30s" 
                     repeatCount="indefinite"/>
          </feTurbulence>
          <feDisplacementMap in="SourceGraphic" scale="50"/>
        </filter>
      </defs>
      <rect width="100%" height="100%" fill="url(#grad1)" filter="url(#turbulence)"/>
    </svg>

    <!-- Middle Layer -->
    <svg viewBox="0 0 100 100" preserveAspectRatio="none">
      <defs>
        <filter id="glow">
          <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
          <feMerge>
            <feMergeNode in="coloredBlur"/>
            <feMergeNode in="SourceGraphic"/>
          </feMerge>
        </filter>
      </defs>
      
      <!-- Flowing lines -->
      <path d="M0,50 Q25,30 50,50 T100,50" 
            fill="none" 
            stroke="rgba(255,255,255,0.2)" 
            stroke-width="0.2" 
            filter="url(#glow)">
        <animate attributeName="d" 
                 values="M0,50 Q25,30 50,50 T100,50;
                        M0,50 Q25,70 50,50 T100,50;
                        M0,50 Q25,30 50,50 T100,50" 
                 dur="20s" 
                 repeatCount="indefinite"/>
      </path>
      
      <!-- Multiple flowing paths with different timings -->
      <g opacity="0.6">
        <path d="M0,20 Q30,40 50,20 T100,20" 
              fill="none" 
              stroke="rgba(138,43,226,0.2)" 
              stroke-width="0.2" 
              filter="url(#glow)">
          <animate attributeName="d" 
                   values="M0,20 Q30,40 50,20 T100,20;
                          M0,20 Q30,0 50,20 T100,20;
                          M0,20 Q30,40 50,20 T100,20" 
                   dur="15s" 
                   repeatCount="indefinite"/>
        </path>
        
        <path d="M0,80 Q30,100 50,80 T100,80" 
              fill="none" 
              stroke="rgba(65,105,225,0.2)" 
              stroke-width="0.2" 
              filter="url(#glow)">
          <animate attributeName="d" 
                   values="M0,80 Q30,100 50,80 T100,80;
                          M0,80 Q30,60 50,80 T100,80;
                          M0,80 Q30,100 50,80 T100,80" 
                   dur="18s" 
                   repeatCount="indefinite"/>
        </path>
      </g>
    </svg>

    <!-- Foreground Layer -->
    <svg viewBox="0 0 100 100" preserveAspectRatio="none">
      <!-- Particles -->
      <g fill="white" opacity="0.5">
        <circle cx="10" cy="10" r="0.2">
          <animate attributeName="cy" 
                   values="10;90;10" 
                   dur="20s" 
                   repeatCount="indefinite"/>
          <animate attributeName="opacity" 
                   values="0;1;0" 
                   dur="20s" 
                   repeatCount="indefinite"/>
        </circle>
        <circle cx="30" cy="20" r="0.15">
          <animate attributeName="cy" 
                   values="20;80;20" 
                   dur="25s" 
                   repeatCount="indefinite"/>
          <animate attributeName="opacity" 
                   values="0;1;0" 
                   dur="25s" 
                   repeatCount="indefinite"/>
        </circle>
        <circle cx="70" cy="30" r="0.25">
          <animate attributeName="cy" 
                   values="30;70;30" 
                   dur="15s" 
                   repeatCount="indefinite"/>
          <animate attributeName="opacity" 
                   values="0;1;0" 
                   dur="15s" 
                   repeatCount="indefinite"/>
        </circle>
      </g>
    </svg>
  </div>

  <div class="art-overlay">
    <h1>Ethereal Flow</h1>
  </div>
</body>
</html>