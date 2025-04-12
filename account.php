<?php?>

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
    <title>Account</title>
</head>

<body class="bg-black">
   <section>
        <div class="container mx-auto px-10 py-12">
            <div class="py-10">
                <h2 class="text-3xl font-black text-gray-100">Hello, &nbsp;<span class="text-[#3E25F6] hover:underline">Ashish</span> !</h2>
            </div>
            <div class="grid grid-cols-3 gap-5">
                <div class="col-span-1 bg-[#FDC540] py-20 text-center rounded-2xl hover:scale-105 transition-all duration-300">
                    <a href="find.php" class="text-black font-bold text-5xl">Find</a>
                </div>
                <div class="col-span-1 bg-[#FF664C] py-20 text-center rounded-2xl hover:scale-105 transition-all duration-300">
                    <a href="lost.php" class="text-black font-bold text-5xl">Lost</a>
                </div>
                <div class="col-span-1 bg-[#84DB8C] py-20 text-center rounded-2xl hover:scale-105 transition-all duration-300">
                    <a href="view_message.php" class="text-black font-bold text-5xl">View Message</a>
                </div>
            </div>
        </div>
   </section>
</body>
</html>