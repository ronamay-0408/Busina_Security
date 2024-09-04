
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const output = document.getElementById('output');
    const context = canvas.getContext('2d');

    // Configure video constraints for optimal performance
    const videoConstraints = {
        facingMode: 'environment',
        frameRate: { ideal: 30, max: 30 } // Adjust frame rate as needed
    };

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: videoConstraints })
            .then(function(stream) {
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();
                requestAnimationFrame(tick);
            })
            .catch(function(err) {
                console.error("Error accessing the camera: ", err);
                output.textContent = "Error accessing the camera.";
            });
    }

    startCamera();

    let lastScannedQR = null;

    // Initialize the web worker for QR code processing
    const worker = new Worker("{{ asset('js/qr-worker.js') }}");

    worker.onmessage = function(e) {
        const result = e.data;
        if (result.success) {
            // QR Code detected
            if (result.data !== lastScannedQR) {
                console.log("QR Code found:", result.data);
                output.textContent = `QR Code Data: ${result.data}`;

                sendQRCodeDataToServer(result.data);

                lastScannedQR = result.data;

                // Reset lastScannedQR to null after a short delay
                setTimeout(() => {
                    output.textContent = "Scanning...";
                    lastScannedQR = null;
                }, 1000);
            }
        } else {
            // No QR Code detected
            output.textContent = "Scanning for QR code...";
        }
    };

    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

            // Send image data to the worker for processing
            worker.postMessage({ imageData: imageData });
        }
        requestAnimationFrame(tick);
    }

    // Debounced function for sending QR code data to the server
    const sendQRCodeDataToServer = debounce(function(qrCodeData) {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
            console.error('CSRF token meta tag not found');
            alert('CSRF token not found.');
            return;
        }

        const csrfToken = csrfTokenMeta.getAttribute('content');
        fetch('{{ route("scan.qr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ qr_code: qrCodeData })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data);
            if (data.success) {
                window.location.href = '{{ route("scanned.result") }}';
            } else {
                if (data.redirect) {
                    window.location.href = data.redirect; // Redirect to the URL provided in the response
                } else {
                    alert("Error: " + (data.message || "Unknown error occurred"));
                }
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error occurred while scanning QR code.");
        });
    }, 500); // Debounce for 0.5 seconds

    // Debounce function definition
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }