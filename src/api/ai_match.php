<?php
header('Content-Type: application/json');
require_once '../config/db_connect.php'; 

$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs
$itemName = mysqli_real_escape_string($conn, $input['item_name']);
$location = mysqli_real_escape_string($conn, $input['location']);
$currentType = $input['current_type'];
$currentImage = mysqli_real_escape_string($conn, $input['current_image']);

// Determine which table to search
$searchTable = ($currentType === 'lost') ? 'found_items' : 'lost_items';

$query = "SELECT * FROM $searchTable 
          WHERE item_name LIKE '%$itemName%' 
          AND location LIKE '%$location%' 
          ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

$matches = [];
while ($row = mysqli_fetch_assoc($result)) {
    $matches[] = [
        'image_path' => 'http://localhost/debugNinjas/src/img/upload/' . $row['image_path'],
        'item_name' => $row['item_name'],
        'location' => $row['location'],
        'date' => $row['created_at']
    ];
}

echo json_encode($matches);
?>