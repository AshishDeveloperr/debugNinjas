<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

$user = $_GET['user'] ?? '';

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
                <?php
                   
                    $sql = mysqli_query($conn, "SELECT * FROM `messages` ORDER BY `id` DESC");

                    while($row = mysqli_fetch_assoc($sql)){
                ?>
                <div class="bg-[#FDC540] rounded-xl p-3 transition-all duration-500 ease-out cursor-pointer card flex justify-between items-center my-3">
                    <h2 class="text-lg font-semibold text-white tracking-wider"><?php echo ucfirst($row['sender'])?></h2>
                    <a href="view_message_details.php?user=<?php echo $row['sender']?>&id=<?php echo $row['id']?>" class="text-base font-semibold text-white bg-[#3E25F6] px-6 py-3 rounded-md">View Message</a>
                </div>
                <?php 
                }?>
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