
const uploadInput = document.getElementById('upload');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const downloadBtn = document.getElementById('download-btn');
const dropZone = document.getElementById('drop-zone');

// Function to process the image and make it symmetrical across both axes
function makeSymmetrical(image) {
    const width = image.width;
    const height = image.height;

    // Resize canvas to match image dimensions
    canvas.width = width;
    canvas.height = height;

    // Draw the image to the left half of the canvas (mirrored on the X axis)
    ctx.drawImage(image, 0, 0, width / 2, height / 2); // Top-left quadrant

    // Flip and draw the right half (mirrored on the X axis)
    ctx.save();
    ctx.scale(-1, 1); // Flip the context horizontally
    ctx.drawImage(image, -width, 0, width / 2, height / 2); // Top-right quadrant
    ctx.restore();

    // Flip and draw the bottom-left half (mirrored on the Y axis)
    ctx.save();
    ctx.scale(1, -1); // Flip the context vertically
    ctx.drawImage(image, 0, -height, width / 2, height / 2); // Bottom-left quadrant
    ctx.restore();

    // Flip and draw the bottom-right half (mirrored on both X and Y axes)
    ctx.save();
    ctx.scale(-1, -1); // Flip the context both horizontally and vertically
    ctx.drawImage(image, -width, -height, width / 2, height / 2); // Bottom-right quadrant
    ctx.restore();
}

// Function to download the processed image
function downloadImage() {
    const dataURL = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = 'symmetrical-image.png';
    link.click();
}

// Event listener for file upload
uploadInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function() {
            const img = new Image();
            img.onload = function() {
                makeSymmetrical(img);
                downloadBtn.style.display = 'inline-block'; // Show the download button
            };
            img.src = reader.result;
        };

        reader.readAsDataURL(file);
    }
});

// Event listener for the download button
downloadBtn.addEventListener('click', downloadImage);

// Drag and Drop functionality
dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('hover');
});

dropZone.addEventListener('dragleave', function() {
    dropZone.classList.remove('hover');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('hover');

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function() {
            const img = new Image();
            img.onload = function() {
                makeSymmetrical(img);
                downloadBtn.style.display = 'inline-block'; // Show the download button
            };
            img.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
});