// public/js/qr-worker.js
importScripts('https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js');

onmessage = function(e) {
    const imageData = e.data.imageData;
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: "dontInvert",
    });

    if (code && code.data) {
        postMessage({ success: true, data: code.data });
    } else {
        postMessage({ success: false });
    }
};
