const baseImageInput = document.getElementById('baseImage');
const otherImagesInput = document.getElementById('otherImages');
const processButton = document.getElementById('process');
const outputDiv = document.getElementById('output');
const brightnessSlider = document.getElementById('brightness');
const loadingIndicator = document.getElementById('loading');
const downloadAllButton = document.getElementById('downloadAll');

// Get the new width and height input elements
const outputWidthInput = document.getElementById('outputWidth');
const outputHeightInput = document.getElementById('outputHeight');

let baseImageData;

baseImageInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            baseImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        };
        img.src = URL.createObjectURL(file);
    }
});

processButton.addEventListener('click', () => {
    if (!baseImageData) {
        alert("Please upload a base image.");
        return;
    }
    if (!otherImagesInput.files.length) {
        alert("Please upload one or more images to match.");
        return;
    }

    const upscaleWidth = parseInt(outputWidthInput.value) || 2048;
    const upscaleHeight = parseInt(outputHeightInput.value) || 2048;

    outputDiv.innerHTML = '';
    loadingIndicator.style.display = 'block';
    downloadAllButton.style.display = 'none';

    const averageColor = getAverageColor(baseImageData);
    const brightness = parseFloat(brightnessSlider.value);
    const processedImages = [];

    Array.from(otherImagesInput.files).forEach(file => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = upscaleWidth;
            canvas.height = upscaleHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, upscaleWidth, upscaleHeight);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            
            adjustColors(imageData, averageColor, brightness);

            ctx.putImageData(imageData, 0, 0);
            const outputImage = document.createElement('img');
            outputImage.src = canvas.toDataURL();
            outputImage.className = 'output-image';

            processedImages.push({ src: canvas.toDataURL(), name: `processed_${file.name}` });

            const downloadLink = document.createElement('a');
            downloadLink.href = canvas.toDataURL();
            downloadLink.download = `processed_${file.name}`;
            downloadLink.innerText = 'Download';
            downloadLink.style.display = 'block';
            downloadLink.style.marginTop = '5px';

            outputDiv.appendChild(outputImage);
            outputDiv.appendChild(downloadLink);
            
            if (outputDiv.children.length === otherImagesInput.files.length * 2) {
                loadingIndicator.style.display = 'none';
                downloadAllButton.style.display = 'inline-block';
            }
        };
        img.src = URL.createObjectURL(file);
    });

    downloadAllButton.addEventListener('click', () => {
        const zip = new JSZip();
        processedImages.forEach(image => {
            const imgData = image.src.split(',')[1];
            zip.file(image.name, imgData, { base64: true });
        });

        zip.generateAsync({ type: "blob" }).then(content => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(content);
            link.download = "processed_images.zip";
            link.click();
        });
    });
});

function getAverageColor(imageData) {
    const data = imageData.data;
    let r = 0, g = 0, b = 0, count = 0;

    for (let i = 0; i < data.length; i += 4) {
        r += data[i];
        g += data[i + 1];
        b += data[i + 2];
        count++;
    }

    return {
        r: Math.floor(r / count),
        g: Math.floor(g / count),
        b: Math.floor(b / count),
    };
}

function adjustColors(imageData, averageColor, brightness) {
    const data = imageData.data;

    for (let i = 0; i < data.length; i += 4) {
        data[i] = ((data[i] + averageColor.r) / 2) * brightness;
        data[i + 1] = ((data[i + 1] + averageColor.g) / 2) * brightness;
        data[i + 2] = ((data[i + 2] + averageColor.b) / 2) * brightness;
    }
}