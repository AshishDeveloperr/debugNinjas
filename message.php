<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

$receiver = $_GET['rec'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        die(json_encode(['status' => 'error', 'message' => 'Not authenticated']));
    }

    // Sanitize inputs
    $sender = $_SESSION['username'];
    $receiver = filter_var($_POST['receiver_username'], FILTER_SANITIZE_STRING);
    $message = htmlspecialchars($_POST['message']);
    $itemId = filter_var($_POST['item_id'], FILTER_SANITIZE_NUMBER_INT);
    $itemType = strtolower(filter_var($_POST['item_type'], FILTER_SANITIZE_STRING));

    // Validate item type
    if (!in_array($itemType, ['lost', 'found'])) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid item type']));
    }

    // Validate receiver exists
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $receiver);
    $stmt->execute();
    if (!$stmt->get_result()->num_rows) {
        die(json_encode(['status' => 'error', 'message' => 'Receiver not found']));
    }

    // Insert message using new structure
    $stmt = $conn->prepare("INSERT INTO messages 
        (sender, receiver, message, item_id, item_type) 
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $sender, $receiver, $message, $itemId, $itemType);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Message sent!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
}

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
                <h2 class="text-4xl font-black text-gray-100">Hello, &nbsp;<span class="text-[#3E25F6] hover:underline">Ashish</span> !</h2>
            </div>
            <div class="max-w-3xl mx-auto">
                <div id="contactForm" class=" mt-6 bg-white p-6 rounded-lg">
                    <h3 class="text-xl font-bold mb-4">Contact Owner</h3>
                    <form id="messageForm" method="POST">
                        <div class="mb-6">
                            <label for="receiverUsername" class="block text-gray-700 font-medium mb-2">Owner's Name</label>
                            <input type="text" id="receiverUsername" name="receiver_username" value="<?php echo $receiver;?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                        </div>
                        <div class="mb-6">
                            <label for="item_type" class="block text-gray-700 font-medium mb-2">Lost or Found</label>
                            <input type="text" id="itemType" name="item_type" required="" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                        </div>
                        <div class="mb-6">
                            <label for="item_id" class="block text-gray-700 font-medium mb-2">Item Name</label>
                            <input type="text" id="itemId" name="item_id" required="" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#FDC540]">
                        </div>
                        <div class="mb-4">
                            <textarea id="messageContent" name="message" 
                                class="w-full text-gray-700 p-3 rounded-lg focus:outline-none focus:border-[#FDC540] border"
                                placeholder="Write your message..." required></textarea>
                        </div>
                        <button type="submit" 
                            class="bg-[#FDC540] text-black px-6 py-2 rounded-lg hover:bg-[#FF664C] transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
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