<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

$image1 = $_GET['img1'] ?? '';
$image2 = $_GET['img2'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/output.css">
    <link rel="stylesheet" href="src/css/custom.css">
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hubot+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <title>Home Page</title>
    <style>
        .card:hover{
            border-right: 10px solid #3E25F6;
            border-bottom: 10px solid #3E25F6;
            transition: all 0.3s ease-in-out;
            transform: translateY(-6px);
        }
    </style>
</head>

<body class="bg-black">
   <section>
        <div class="container mx-auto px-10 py-12">
            <div class="py-10">
                <h2 class="text-3xl font-black text-gray-100">Hello, &nbsp;<span class="text-[#3E25F6] hover:underline">Ashish</span> !</h2>
                <div class="flex space-x-4 my-6">
                    <p class="text-base font-semibold text-[#FDC540] tracking-wider">All items (21)</p>
                    <p class="text-base font-semibold text-[#FF664C] tracking-wider">Lost (10)</p>
                </div>
            </div>
            <div class="grid grid-cols-2 text-white gap-8 max-w-3xl">
                <div class="image-box col-span-1 bg-[#181818] p-6 rounded-2xl">
                    <div class="h-48 overflow-hidden rounded-xl">
                        <img id="lostPreview" class="image-preview w-full object-cover" alt="Lost item preview">
                    </div>
                    <h3 class="text-base font-semibold text-white mt-6">🕵️ Lost Item</h3>
                    <input type="url" id="lostImage" placeholder="Paste lost item image URL" class="bg-white w-full my-3 text-black invisible" value="http://localhost/debugNinjas/src/img/upload/<?= htmlspecialchars($image1) ?>">
                </div>
                
                <div class="image-box col-span-1 bg-[#181818] p-6 rounded-2xl">
                    <div class="h-48 overflow-hidden rounded-xl">
                        <img id="foundPreview" class="image-preview w-full object-cover" alt="Found item preview">
                    </div>
                    <h3 class="text-base font-semibold text-white mt-6">🎉 Found Item</h3>
                    <input type="url" id="foundImage" placeholder="Paste found item image URL" class="bg-white w-full my-3 text-black invisible" value="<?= htmlspecialchars($image2) ?>">
                </div>
            </div>

            <button onclick="compareImages()" class="text-white px-6 py-2 bg-[#3E25F6] my-12 font-semibold rounded-md cursor-pointer">
                
                Compare Items
            </button>
            <div id="result" class="text-white py-3 "></div>
        </div>
    </section>
    <!-- celebrate -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
   <script>

const API_KEY = 'AIzaSyC5KrTrQMU_Tbkb2YwGEB78RDlO8-rNCtI'; 
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

// Live image preview
window.addEventListener('DOMContentLoaded', () => {
    const lostInput = document.getElementById('lostImage');
    const foundInput = document.getElementById('foundImage');
    
    // Set initial previews from input values
    if (lostInput.value) {
        document.getElementById('lostPreview').src = lostInput.value;
    }
    if (foundInput.value) {
        document.getElementById('foundPreview').src = foundInput.value;
    }
});

// Live preview updates
document.getElementById('lostImage').addEventListener('input', function() {
    const preview = document.getElementById('lostPreview');
    preview.src = this.value;
    preview.onerror = () => {
        preview.src = 'placeholder.jpg'; // Fallback image
        this.value = ''; // Clear invalid input
    };
});

document.getElementById('foundImage').addEventListener('input', function() {
    const preview = document.getElementById('foundPreview');
    preview.src = this.value;
    preview.onerror = () => {
        preview.src = 'placeholder.jpg'; // Fallback image
        this.value = ''; // Clear invalid input
    };
});

async function compareImages() {
    const lostImg = document.getElementById('lostImage').value;
    const foundImg = document.getElementById('foundImage').value;
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = "";

    try {
        // Validate inputs
        if (!isValidUrl(lostImg)) throw new Error('Invalid lost item URL');
        if (!isValidUrl(foundImg)) throw new Error('Invalid found item URL');

        resultDiv.innerHTML = "<div class='loading'>🔍 Analyzing images...</div>";

        // Convert URLs to base64
        const [lostBase64, foundBase64] = await Promise.all([
            urlToBase64(lostImg),
            urlToBase64(foundImg)
        ]);

        // Gemini API request
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                contents: [{
                    parts: [
                        { text: "Analyze these two images of lost and found items. Are they likely the same item? Consider color, shape, size, and unique features. Respond only with: 'Match (X%)' or 'No Match (X%)' where X is your confidence percentage." },
                        { inline_data: { mime_type: "image/jpeg", data: lostBase64 } },
                        { inline_data: { mime_type: "image/jpeg", data: foundBase64 } }
                    ]
                }]
            })
        });

        const data = await response.json();
        
        // Error handling
        if (data.error) throw new Error(`API Error: ${data.error.message}`);
        if (!data.candidates?.[0]?.content?.parts?.[0]?.text) {
            throw new Error('Unexpected API response format');
        }

        // Display result
        const result = data.candidates[0].content.parts[0].text;
        resultDiv.innerHTML = `<div class="result bg-green-500 text-white text-lg font-bold tracking-widest inline px-10 py-3 rounded-md">${result.replace(/(\d+%)/, '<strong>$1</strong>')}</div>`;

    } catch (error) {
        resultDiv.innerHTML = `<div class="error text-black bg-red-700 text-base font-bold tracking-wider">❌ Error: ${error.message}</div>`;
        console.error('Error:', error);
    }
}

// Utility functions
function isValidUrl(url) {
    try { return Boolean(new URL(url)); }
    catch { return false; }
}

async function urlToBase64(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to load image');
    const blob = await response.blob();
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result.split(',')[1]);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}

</script>
</body>
</html>