<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

$user = $_GET['user'] ?? '';
$id = $_GET['id'] ?? '';

$sender = '';
$receiver = '';
$message = '';
$itemType = '';

    $stmt = $conn->prepare("SELECT * FROM messages WHERE id = ? AND receiver = ?");
    $stmt->bind_param("is", $id, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the data
        $row = $result->fetch_assoc();
        $sender = $row['sender'];
        $receiver = $row['receiver'];
        $message = $row['message'];
        $itemType = $row['item_type'];

    } else {
        echo '<div class="alert alert-danger text-white" id="toast-alert">No message found.</div>';
    }
    $stmt->close();

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
                <h2 class="text-4xl font-black text-gray-100">Hello, &nbsp;<span class="text-[#3E25F6] hover:underline"><?php echo ucfirst($user);?></span> !</h2>
            </div>
            <div class="max-w-3xl mx-auto">
                
                <div class="bg-white border rounded-md p-4">
                    <h2 class="text-lg font-semibold text-black tracking-wider">Message Details</h2>
                    <div class="flex justify-between items-center my-3">
                        <h2 class="text-lg font-semibold text-black tracking-wider"><span class="font-bold text-teal-600 ">Sender Name: </span><?php echo ucfirst($sender)?></h2>
                        <a href="view_message.php?user=<?php echo $user?>" class="text-base font-semibold text-white bg-[#3E25F6] px-6 py-3 rounded-md">Back</a>
                    </div>
                    <p class="text-base font-semibold text-black tracking-wider"><span class="font-bold text-teal-600">Info: </span><?php echo ucfirst($itemType)?></p>
                    <p class="text-base font-semibold text-black tracking-wider"><span class="font-bold text-teal-600">Message: </span><?php echo ucfirst($message)?></p>
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