<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $user_name = htmlspecialchars(trim($_POST['user_name']));
    $item_name = htmlspecialchars(trim($_POST['item_name']));
    $location = htmlspecialchars(trim($_POST['location']));
    $found_date = $_POST['found_date'];
    
    // Handle file upload
    $target_dir = "src/img/upload/";
    $original_name = basename($_FILES["image"]["name"]);
    $fileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $random_string = bin2hex(random_bytes(8));
    $new_filename = "found_" . $user_name . "_" . $random_string . "." . $fileType;
    $target_file = $target_dir . $new_filename;

    // Validate image
    $allowed_types = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($fileType, $allowed_types)) {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Only JPG, JPEG, PNG & WEBP files are allowed.</div>', $errorAlert);
        echo $errorAlert;
    }

    if ($_FILES["image"]["size"] > 5000000) {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">File size exceeds 5MB limit.</div>', $errorAlert);
        echo $errorAlert;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO found_items 
            (user_name, item_name, location, found_date, image_path) 
            VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssss", 
            $user_name,
            $item_name,
            $location,
            $found_date,
            $new_filename
        );

        if ($stmt->execute()) {
            $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Found item reported successfully!</div>', $successAlert);
            echo $successAlert;
        } else {
            $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Database error.</div>', $errorAlert);
            echo $errorAlert;
        }
        $stmt->close();
    } else {
        $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Error uploading file.</div>', $errorAlert);
        echo $errorAlert;
    }
}

$conn->close();

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
    <title>Report Fount Item</title>
</head>

<body class="bg-black">
   <section>
        <div class="container mx-auto px-10 py-12">
            <div class="py-10">
                <h2 class="text-3xl font-black text-gray-100">Hello, &nbsp;<span class="text-[#3E25F6] hover:underline">Ashish</span> !</h2>
            </div>
            <div class="">
                <form method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold text-[#3E25F6] mb-6">Report Found Item</h2>

                    <div class="mb-6">
                        <label for="user_name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                        <input type="text" id="user_name" name="user_name" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                    </div>

                    <div class="mb-6">
                        <label for="item_name" class="block text-gray-700 font-medium mb-2">Item Name</label>
                        <input type="text" id="item_name" name="item_name" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                    </div>

                    <div class="mb-6">
                        <label for="location" class="block text-gray-700 font-medium mb-2">Found Location</label>
                        <input type="text" id="location" name="location" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                    </div>

                    <div class="mb-6">
                        <label for="found_date" class="block text-gray-700 font-medium mb-2">Date Found</label>
                        <input type="date" id="found_date" name="found_date" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                    </div>

                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 font-medium mb-2">Upload Image</label>
                        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/webp" required
                            class="w-full px-4 py-2 border rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-[#FDC540] file:text-black hover:file:bg-[#3E25F6] hover:file:text-white">
                    </div>

                    <button type="submit" 
                        class="w-full bg-[#FDC540] text-black py-3 px-6 rounded-lg hover:bg-[#3E25F6] hover:text-white transition-all duration-300">
                        Submit Found Item
                    </button>
                </form>
            </div>
        </div>
   </section>
   <script>
      function dismissAlert(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.style.opacity = 0;
            setTimeout(() => {
                alertElement.style.display = "none";
            }, 300);
        }
    }

    setTimeout(function() {
        const alertElement = document.getElementById("toast-alert");
        if (alertElement) {
            alertElement.style.opacity = 0;
            setTimeout(() => {
                alertElement.style.display = "none";
            }, 300);
        }
    }, 5000);

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    </script>
</body>
</html>