<?php

include '../components/connect.php';

if (isset($_POST['submit'])) {

   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

   $select_tutor = $conn->prepare("SELECT * FROM tutors WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if ($select_tutor->rowCount() > 0) {
      setcookie('tutor_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
      header('location:dashboard.php');
   } else {
      $message[] = 'Incorrect email or password!';
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Favicon -->
   <link rel="icon" href="../images/favicon.png" type="image/x-icon">
</head>

<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- login section starts -->
<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>welcome back!</h3>

      <p>your email <span>*</span></p>
      <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">

      <p>your password <span>*</span></p>
      <div style="position: relative; width: 100%;">
         <input type="password" name="pass" id="password" placeholder="enter your password"
         maxlength="50" required class="box" style="width: 100%;">
         <span id="togglePassword"
         style="position:absolute; right:15px; top:50%; transform:translateY(-50%);
         cursor:pointer; color:#888; font-size:16px;">
            <i class="fa-solid fa-eye"></i>
         </span>
      </div>

      <p class="link">Don't have an account? <a href="register.php">Register Now</a></p>
      <input type="submit" name="submit" value="login now" class="btn">
   </form>

</section>
<!-- login section ends -->

<!-- Dark mode (your same logic) -->
<script>
let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}
</script>

<!-- Show / Hide Password -->
<script>
document.addEventListener("DOMContentLoaded", function() {
   const togglePassword = document.getElementById("togglePassword");
   const password = document.getElementById("password");
   const icon = togglePassword.querySelector("i");

   togglePassword.addEventListener("click", function () {
      const isPassword = password.type === "password";
      password.type = isPassword ? "text" : "password";

      // Toggle eye icon
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
   });
});
</script>

</body>
</html>
