<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
</head>
<body>
    <video id="video" playsinline autoplay></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <div id="output"></div>
    <a id="link" href="#" style="display:none;" target="_blank">Go to Link</a>
    
    <script src="https://cdn.rawgit.com/cozmo/jsQR/master/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const output = document.getElementById('output');
        const link = document.getElementById('link');
        const context = canvas.getContext('2d');
        let scanning = false;

        navigator.mediaDevices
            .getUserMedia({ video: { facingMode: 'environment' } })
            .then(function (stream) {
                video.srcObject = stream;
            })
            .catch(function (error) {
                console.error('Error accessing the camera:', error);
            });

        video.addEventListener('canplay', function () {
            if (!scanning) {
                scanning = true;
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                requestAnimationFrame(scan);
            }
        });

        function scan() {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                output.textContent = 'QR Code: ' + code.data;
                link.href = code.data;
                link.style.display = 'block'; // Show the link
            }

            if (scanning) {
                requestAnimationFrame(scan);
            }
        }
    </script>
</body>
</html>
