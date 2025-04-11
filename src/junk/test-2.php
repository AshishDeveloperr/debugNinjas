<?php
// Clarifai API details
// $apiUrl = "https://api.clarifai.com/v2/users/qwen/apps/qwen-VL/models/Qwen2_5-VL-7B-Instruct/versions/d937b8b81dff4b1280c91174d1762345/outputs";
// $apiKey = "86c6bc453c0049e694ddee0637e98e05"; // Replace this with your actual API key

// URLs of the images
$image1 = "https://res.cloudinary.com/di7npwwyd/image/upload/v1744369310/WhatsApp_Image_2025-04-11_at_16.30.10_yitwma.jpg";
$image2 = "https://res.cloudinary.com/di7npwwyd/image/upload/v1744369311/WhatsApp_Image_2025-04-11_at_16.30.11_qgpjow.jpg";

$apiKey = '86c6bc453c0049e694ddee0637e98e05';
$userId = 'qwen';
$appId = 'qwen-VL';

$image1 = 'https://example.com/image1.jpg';
$image2 = 'https://example.com/image2.jpg';

// Function to add an image to Clarifai inputs
function addImageToClarifai($imageUrl, $apiKey, $userId, $appId) {
    $url = "https://api.clarifai.com/v2/users/$userId/apps/$appId/inputs";
    $data = [
        "inputs" => [
            [
                "data" => [
                    "image" => [
                        "url" => $imageUrl
                    ]
                ]
            ]
        ]
    ];
    $options = [
        "http" => [
            "header" => "Authorization: Key $apiKey\r\n" .
                        "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Function to search for similar images
function searchSimilarImages($queryImageUrl, $apiKey, $userId, $appId) {
    $url = "https://api.clarifai.com/v2/users/$userId/apps/$appId/inputs/searches";
    $data = [
        "query" => [
            "ands" => [
                [
                    "input" => [
                        "data" => [
                            "image" => [
                                "url" => $queryImageUrl
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
    $options = [
        "http" => [
            "header" => "Authorization: Key $apiKey\r\n" .
                        "Content-Type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Add both images to Clarifai
addImageToClarifai($image1, $apiKey, $userId, $appId);
addImageToClarifai($image2, $apiKey, $userId, $appId);

// Search for similar images using image1
$searchResults = searchSimilarImages($image1, $apiKey, $userId, $appId);

// Check if image2 is among the search results
$matched = false;
foreach ($searchResults['hits'] as $hit) {
    if ($hit['input']['data']['image']['url'] === $image2) {
        $score = $hit['score'] * 100; // Convert to percentage
        echo "Similarity Score: $score%\n";
        if ($score >= 70) {
            echo "Matched! Images are at least 70% similar.";
        } else {
            echo "Not Matched! Images are less than 70% similar.";
        }
        $matched = true;
        break;
    }
}

if (!$matched) {
    echo "Image2 not found in the search results.";
}
?>
