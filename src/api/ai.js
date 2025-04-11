
const API_KEY = 'AIzaSyC5KrTrQMU_Tbkb2YwGEB78RDlO8-rNCtI'; // Replace with your actual API key
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

// Live image preview
document.getElementById('lostImage').addEventListener('input', function() {
    document.getElementById('lostPreview').src = this.value;
});

document.getElementById('foundImage').addEventListener('input', function() {
    document.getElementById('foundPreview').src = this.value;
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
        resultDiv.innerHTML = `<div class="result">${result.replace(/(\d+%)/, '<strong>$1</strong>')}</div>`;

    } catch (error) {
        resultDiv.innerHTML = `<div class="error">❌ Error: ${error.message}</div>`;
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