const canvas = document.getElementById('canvas');
let shapes = [];
let selectedShape = null;
let isDragging = false;
let isRotating = false;
let isResizing = false;
let startX, startY, originalX, originalY, originalRotation, originalWidth, originalHeight;
let resizeHandle = '';

// Add keydown event listener for delete key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Delete' || e.key === 'Backspace') {
    deleteSelected();
  }
});

function deleteSelected() {
  if (selectedShape) {
    const index = shapes.indexOf(selectedShape);
    if (index > -1) {
      shapes.splice(index, 1);
    }
    selectedShape.remove();
    selectedShape = null;
  }
}

function createShape(x, y) {
  const shapeType = document.getElementById('shapeSelect').value;
  const fillColor = document.getElementById('fillColor').value;
  const strokeColor = document.getElementById('strokeColor').value;
  const strokeWidth = document.getElementById('strokeWidth').value;
  const opacity = document.getElementById('opacity').value;

  const shape = document.createElement('div');
  shape.classList.add('shape');
  shape.dataset.type = shapeType;
  shape.dataset.rotation = '0';
  shape.style.left = `${x}px`;
  shape.style.top = `${y}px`;
  shape.style.opacity = opacity;

  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('width', '100%');
  svg.setAttribute('height', '100%');

  let svgShape;
  switch (shapeType) {
    case 'rect':
      shape.style.width = '100px';
      shape.style.height = '60px';
      svgShape = createSVGElement('rect', {
        width: '100%',
        height: '100%',
        fill: fillColor,
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;

    case 'circle':
      shape.style.width = '80px';
      shape.style.height = '80px';
      svgShape = createSVGElement('circle', {
        cx: '50%',
        cy: '50%',
        r: '40%',
        fill: fillColor,
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;

    case 'ellipse':
      shape.style.width = '100px';
      shape.style.height = '60px';
      svgShape = createSVGElement('ellipse', {
        cx: '50%',
        cy: '50%',
        rx: '45%',
        ry: '45%',
        fill: fillColor,
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;

    case 'line':
      shape.style.width = '100px';
      shape.style.height = '2px';
      svgShape = createSVGElement('line', {
        x1: '0',
        y1: '50%',
        x2: '100%',
        y2: '50%',
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;

    case 'triangle':
      shape.style.width = '100px';
      shape.style.height = '100px';
      svgShape = createSVGElement('polygon', {
        points: '50,10 90,90 10,90',
        fill: fillColor,
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;

    case 'star':
      shape.style.width = '100px';
      shape.style.height = '100px';
      const points = generateStarPoints(5, 40, 20);
      svgShape = createSVGElement('polygon', {
        points: points,
        fill: fillColor,
        stroke: strokeColor,
        'stroke-width': strokeWidth
      });
      break;
  }

  svg.appendChild(svgShape);
  shape.appendChild(svg);
  
  // Modified event listeners
  shape.addEventListener('mousedown', startDrag);
  shape.addEventListener('click', (e) => {
    e.stopPropagation();
    selectShape(shape);
  });
  
  canvas.appendChild(shape);
  shapes.push(shape);
  return shape;
}

function createSVGElement(type, attributes) {
  const element = document.createElementNS('http://www.w3.org/2000/svg', type);
  for (const [key, value] of Object.entries(attributes)) {
    element.setAttribute(key, value);
  }
  return element;
}

function generateStarPoints(points, outer, inner) {
  let str = '';
  for (let i = 0; i < points * 2; i++) {
    const radius = i % 2 === 0 ? outer : inner;
    const angle = (i * Math.PI) / points;
    const x = 50 + radius * Math.sin(angle);
    const y = 50 - radius * Math.cos(angle);
    str += `${x},${y} `;
  }
  return str.trim();
}

function addControlHandles(shape) {
  const controls = document.createElement('div');
  controls.classList.add('shape-controls');

  // Rotation handle
  const rotationHandle = document.createElement('div');
  rotationHandle.classList.add('rotation-handle');
  rotationHandle.style.left = '50%';
  rotationHandle.style.top = '-20px';
  rotationHandle.style.transform = 'translateX(-50%)';
  rotationHandle.addEventListener('mousedown', startRotation);
  controls.appendChild(rotationHandle);

  // Resize handles
  const positions = ['nw', 'n', 'ne', 'e', 'se', 's', 'sw', 'w'];
  positions.forEach(pos => {
    const handle = document.createElement('div');
    handle.classList.add('resize-handle');
    handle.dataset.position = pos;
    
    switch(pos) {
      case 'nw': handle.style.left = '-5px'; handle.style.top = '-5px'; break;
      case 'n': handle.style.left = '50%'; handle.style.top = '-5px'; handle.style.transform = 'translateX(-50%)'; break;
      case 'ne': handle.style.right = '-5px'; handle.style.top = '-5px'; break;
      case 'e': handle.style.right = '-5px'; handle.style.top = '50%'; handle.style.transform = 'translateY(-50%)'; break;
      case 'se': handle.style.right = '-5px'; handle.style.bottom = '-5px'; break;
      case 's': handle.style.left = '50%'; handle.style.bottom = '-5px'; handle.style.transform = 'translateX(-50%)'; break;
      case 'sw': handle.style.left = '-5px'; handle.style.bottom = '-5px'; break;
      case 'w': handle.style.left = '-5px'; handle.style.top = '50%'; handle.style.transform = 'translateY(-50%)'; break;
    }

    handle.addEventListener('mousedown', (e) => startResize(e, pos));
    controls.appendChild(handle);
  });

  shape.appendChild(controls);
}

function removeControlHandles(shape) {
  const controls = shape.querySelector('.shape-controls');
  if (controls) {
    controls.remove();
  }
}

function selectShape(shape) {
  if (selectedShape === shape) return;
  
  if (selectedShape) {
    selectedShape.classList.remove('selected');
    removeControlHandles(selectedShape);
  }

  selectedShape = shape;
  selectedShape.classList.add('selected');
  addControlHandles(selectedShape);
}

function startDrag(e) {
  if (e.target.classList.contains('rotation-handle') || e.target.classList.contains('resize-handle')) {
    return;
  }

  const shape = e.target.closest('.shape');
  if (!shape) return;

  // Ensure we select the shape being dragged
  if (selectedShape !== shape) {
    selectShape(shape);
  }

  isDragging = true;
  startX = e.clientX;
  startY = e.clientY;
  originalX = parseInt(shape.style.left) || 0;
  originalY = parseInt(shape.style.top) || 0;

  document.addEventListener('mousemove', drag);
  document.addEventListener('mouseup', stopDrag);
  
  // Prevent default behavior to avoid text selection
  e.preventDefault();
}

function drag(e) {
  if (!isDragging || !selectedShape) return;

  const dx = e.clientX - startX;
  const dy = e.clientY - startY;
  
  selectedShape.style.left = `${originalX + dx}px`;
  selectedShape.style.top = `${originalY + dy}px`;
}

function stopDrag() {
  isDragging = false;
  document.removeEventListener('mousemove', drag);
  document.removeEventListener('mouseup', stopDrag);
}

function startRotation(e) {
  e.stopPropagation();
  isRotating = true;
  const shape = e.target.closest('.shape');
  const rect = shape.getBoundingClientRect();
  const center = {
    x: rect.left + rect.width / 2,
    y: rect.top + rect.height / 2
  };
  
  originalRotation = parseInt(shape.dataset.rotation) || 0;
  startX = Math.atan2(e.clientY - center.y, e.clientX - center.x);

  document.addEventListener('mousemove', rotate);
  document.addEventListener('mouseup', stopRotation);
}

function rotate(e) {
  if (!isRotating || !selectedShape) return;

  const rect = selectedShape.getBoundingClientRect();
  const center = {
    x: rect.left + rect.width / 2,
    y: rect.top + rect.height / 2
  };

  const angle = Math.atan2(e.clientY - center.y, e.clientX - center.x);
  const rotation = originalRotation + (angle - startX) * (180 / Math.PI);
  
  selectedShape.style.transform = `rotate(${rotation}deg)`;
  selectedShape.dataset.rotation = rotation;
}

function stopRotation() {
  isRotating = false;
  document.removeEventListener('mousemove', rotate);
  document.removeEventListener('mouseup', stopRotation);
}

function startResize(e, position) {
  e.stopPropagation();
  isResizing = true;
  resizeHandle = position;
  
  const shape = e.target.closest('.shape');
  startX = e.clientX;
  startY = e.clientY;
  originalWidth = parseInt(shape.style.width);
  originalHeight = parseInt(shape.style.height);
  originalX = parseInt(shape.style.left);
  originalY = parseInt(shape.style.top);

  document.addEventListener('mousemove', resize);
  document.addEventListener('mouseup', stopResize);
}

function resize(e) {
  if (!isResizing || !selectedShape) return;

  const dx = e.clientX - startX;
  const dy = e.clientY - startY;
  
  let newWidth = originalWidth;
  let newHeight = originalHeight;
  let newX = originalX;
  let newY = originalY;

  switch(resizeHandle) {
    case 'nw':
      newWidth = originalWidth - dx;
      newHeight = originalHeight - dy;
      newX = originalX + dx;
      newY = originalY + dy;
      break;
    case 'n':
      newHeight = originalHeight - dy;
      newY = originalY + dy;
      break;
    case 'ne':
      newWidth = originalWidth + dx;
      newHeight = originalHeight - dy;
      newY = originalY + dy;
      break;
    case 'e':
      newWidth = originalWidth + dx;
      break;
    case 'se':
      newWidth = originalWidth + dx;
      newHeight = originalHeight + dy;
      break;
    case 's':
      newHeight = originalHeight + dy;
      break;
    case 'sw':
      newWidth = originalWidth - dx;
      newHeight = originalHeight + dy;
      newX = originalX + dx;
      break;
    case 'w':
      newWidth = originalWidth - dx;
      newX = originalX + dx;
      break;
  }

  // Minimum size constraints
  if (newWidth >= 20 && newHeight >= 20) {
    selectedShape.style.width = `${newWidth}px`;
    selectedShape.style.height = `${newHeight}px`;
    selectedShape.style.left = `${newX}px`;
    selectedShape.style.top = `${newY}px`;
  }
}

function stopResize() {
  isResizing = false;
  document.removeEventListener('mousemove', resize);
  document.removeEventListener('mouseup', stopResize);
}

function generateSVG() {
  const svgContent = `<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">\n${shapes.map(shape => {
    const type = shape.dataset.type;
    const rotation = shape.dataset.rotation || 0;
    const rect = shape.getBoundingClientRect();
    const x = parseInt(shape.style.left);
    const y = parseInt(shape.style.top);
    const width = parseInt(shape.style.width);
    const height = parseInt(shape.style.height);
    const svgElement = shape.querySelector('svg > *');
    const fill = svgElement.getAttribute('fill');
    const stroke = svgElement.getAttribute('stroke');
    const strokeWidth = svgElement.getAttribute('stroke-width');
    
    let shapeCode = '';
    switch(type) {
      case 'rect':
        shapeCode = `  <rect x="${x}" y="${y}" width="${width}" height="${height}" fill="${fill}" stroke="${stroke}" stroke-width="${strokeWidth}" transform="rotate(${rotation} ${x + width/2} ${y + height/2})"/>`;
        break;
      case 'circle':
        const radius = width / 2;
        shapeCode = `  <circle cx="${x + radius}" cy="${y + radius}" r="${radius}" fill="${fill}" stroke="${stroke}" stroke-width="${strokeWidth}" transform="rotate(${rotation} ${x + radius} ${y + radius})"/>`;
        break;
      case 'ellipse':
        const rx = width / 2;
        const ry = height / 2;
        shapeCode = `  <ellipse cx="${x + rx}" cy="${y + ry}" rx="${rx}" ry="${ry}" fill="${fill}" stroke="${stroke}" stroke-width="${strokeWidth}" transform="rotate(${rotation} ${x + rx} ${y + ry})"/>`;
        break;
      case 'line':
        shapeCode = `  <line x1="${x}" y1="${y}" x2="${x + width}" y2="${y}" stroke="${stroke}" stroke-width="${strokeWidth}" transform="rotate(${rotation} ${x + width/2} ${y})"/>`;
        break;
      case 'triangle':
      case 'star':
        const points = svgElement.getAttribute('points');
        shapeCode = `  <polygon points="${points}" fill="${fill}" stroke="${stroke}" stroke-width="${strokeWidth}" transform="translate(${x},${y}) rotate(${rotation} ${width/2} ${height/2})"/>`;
        break;
    }
    return shapeCode;
  }).join('\n')}\n</svg>`;

  document.getElementById('svgOutput').value = svgContent;
}

function downloadSVG() {
  generateSVG();
  const svgContent = document.getElementById('svgOutput').value;
  const blob = new Blob([svgContent], { type: 'image/svg+xml' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'drawing.svg';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

// Initialize canvas click handler
canvas.addEventListener('click', (e) => {
  if (e.target === canvas) {
    if (selectedShape) {
      selectedShape.classList.remove('selected');
      removeControlHandles(selectedShape);
      selectedShape = null;
    }
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    createShape(x, y);
  }
});

// Prevent text selection while dragging
document.addEventListener('selectstart', (e) => {
  if (isDragging || isRotating || isResizing) {
    e.preventDefault();
  }
});