<?php
include 'src/config/session.php';
include 'src/config/db_connect.php';

include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';
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
            <div class="grid grid-cols-4 gap-5">
                <?php
                    if (isset($_REQUEST['category'])) {
                        $postCategory = $_REQUEST['category'];
                        $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_category` = '$postCategory' ORDER BY `sno` DESC LIMIT {$offset},{$postLimit}");  

                    }elseif(isset($_REQUEST['visibility'])){
                        $postVisibility = $_REQUEST['visibility'];
                        $sql = mysqli_query($conn, "SELECT * FROM `posts` WHERE `post_status` = '$postVisibility' ORDER BY `sno` DESC LIMIT {$offset},{$postLimit}");

                    }else{
                        $sql = mysqli_query($conn, "SELECT * FROM `lost_items` ORDER BY `id` DESC");

                    }
                    
                    while($row = mysqli_fetch_assoc($sql)){
                ?>
                <div class="col-span-1 bg-[#181818] rounded-4xl p-5 transition-all duration-500 ease-out cursor-pointer card">
                    <div class="max-h-60 overflow-hidden rounded-2xl">
                        <img src="src/img/upload/<?php echo $row['image_path']?>" alt="" class="rounded-2xl w-full object-cover">
                    </div>
                    <div class="py-4">
                        <h2 class="text-lg font-semibold tracking-wider text-white mb-4 pl-2"><?php echo $row['item_name']?></h2>
                        <p class="text-base font-semibold text-gray-200 flex items-center space-x-2">
                            <svg fill="#FDC540" class="w-6" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  viewBox="0 0 395.71 395.71" xml:space="preserve">
                                <g>
                                    <path d="M197.849,0C122.131,0,60.531,61.609,60.531,137.329c0,72.887,124.591,243.177,129.896,250.388l4.951,6.738c0.579,0.792,1.501,1.255,2.471,1.255c0.985,0,1.901-0.463,2.486-1.255l4.948-6.738c5.308-7.211,129.896-177.501,129.896-250.388C335.179,61.609,273.569,0,197.849,0z M197.849,88.138c27.13,0,49.191,22.062,49.191,49.191c0,27.115-22.062,49.191-49.191,49.191c-27.114,0-49.191-22.076-49.191-49.191C148.658,110.2,170.734,88.138,197.849,88.138z"/>
                                </g>
                            </svg>
                            <?php echo $row['location']?>
                        </p>
                        <p class="text-base font-medium text-gray-200 mt-4 pl-2">
                            <?php 
                                $originalDate = $row['created_at'];
                                
                                $date = new DateTime($originalDate);
                                
                                $formattedDate = $date->format('j F Y \a\t h:i A');
                                echo $formattedDate;
                            ?>
                        </p>
                    </div>
                </div>
                <?php
                } 
                ?>
            </div>
        </div>
   </section>
</body>
</html>