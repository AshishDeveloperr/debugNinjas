<?php
include 'src/config/db_connect.php';
include 'src/alert/alert_success.php';
include 'src/alert/alert_danger.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $user = trim($_POST['user']);
  $password = trim($_POST['password']);
  
  $isEmail = filter_var($user, FILTER_VALIDATE_EMAIL);
  
  $sql = "SELECT sno, username, email, password FROM users WHERE ";
  $sql .= $isEmail ? "email = ?" : "username = ?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $user);
  
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      
      if (password_verify($_POST['password'], $user['password'])) {
          session_start();
          $_SESSION['user_id'] = $user['sno'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
          $successAlert = str_replace('id="success_msg"></div>', 'id="success_msg">Login successfully!</div>', $successAlert);
          echo $successAlert;
          
          header("Location: account.html");
          // exit();
      } else {
          $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">Wrong password.</div>', $errorAlert);
          echo $errorAlert;
      }
  } else {
      $errorAlert = str_replace('id="error_msg"></div>', 'id="error_msg">User not registered.</div>', $errorAlert);
      echo $errorAlert;
  }
  
  $stmt->close();
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
    <title>LOGIN</title>
</head>
<body>
    <div class="w-full h-screen flex flex-col">
        <div class="h-screen grid grid-cols-7 ">
        <div class="visible col-span-7 sm:col-span-4 md:col-span-4 lg:col-span-4 xl:col-span-4  grid h-950px px-6 lg:px-[67px] py-[31px]">
            <div class="grid grid-cols-1">
              <div class="col-span-1 logo ">
                <img src="https://placehold.co/400x200" alt="" class="w-20">
              </div>
            </div>
            <form class="form-login lg:flex justify-center" method="POST">
              <div class="form-login-inner">
                <span class="font-bold text-3xl sm:text-4xl  md:text-5xl lg:text-5xl text-left text-[#313036]">Welcome Back</span>
                <p class="font-semibold text-[#95949E] text-lg pb-12"></p>
                <div class="relative  mt-6">
                  <input placeholder="username" type="text" id="user" name="user" class="w-full bg-white rounded-full border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" required="">
                </div>
                <div class="relative  mt-6">
                  <input placeholder="password" type="password" id="password" name="password" class="w-full bg-white rounded-full border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" required="">
                </div>
                <button class="w-full  text-white bg-[#3E25F6] border-0 py-2 px-6 mt-6 focus:outline-none hover:bg-[#FDC540] hover:text-black transition-all duration-300 rounded-full text-lg cursor-pointer" type="submit">Login</button>
               
                <div class="relative  mt-8 flex justify-center">
  
                  <a href="#" class=" hover:text-[#3E25F6]">Forgot password?</a>
                </div>
                <div class="col-span-1 sm:px-[30px] py-[40px] flex justify-center ">
                  <a href="signup.php" type="button" class="py-2.5 px-5   text-sm font-bold  bg-[#F2F2F3] rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 text-[#313036] focus:transparent ">
                    New to domain? Signup</a>
                </div>
              </div>
            </form>
          </div>
          <div class="hidden sm:col-span-3 md:col-span-3 lg:col-span-3 h-950px xl:col-span-3  sm:block md:block  xl:block 2xl:block">
            <!-- Right column content -->
            <div class="flex items-center justify-center h-screen bg-[#F9F9F9] ">
              <img src="https://placehold.co/1000x1200" alt="login" style="margin-bottom: 3em;">
            </div>
          </div>
   
        </div>
    </div>
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