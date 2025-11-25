<?php

include '../components/connect.php';

$message = [];

if (isset($_POST['submit'])) {

   $id = unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   // Validate passwords
   if ($pass !== $cpass) {
      $message[] = 'Passwords do not match!';
   } else {

      // Handle image upload
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $ext = pathinfo($image, PATHINFO_EXTENSION);
      $rename = unique_id() . '.' . $ext;
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_files/' . $rename;

      // Ensure upload folder exists
      if (!is_dir('../uploaded_files/')) {
         mkdir('../uploaded_files/', 0777, true);
      }

      if ($image_size > 2000000) {
         $message[] = 'Image size too large!';
      } else {
         // Check if email already exists
         $check_email = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
         $check_email->execute([$email]);

         if ($check_email->rowCount() > 0) {
            $message[] = 'Email already registered!';
         } else {
            // Insert without encrypting password
            $insert = $conn->prepare("INSERT INTO `tutors` (id, name, profession, email, password, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$id, $name, $profession, $email, $pass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Registered successfully! You can login now.';
         }
      }
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="icon" href="../images/favicon.png" type="image/x-icon">
</head>
<body style="padding-left: 0;">

<?php
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message form">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Register Form -->
<section class="form-container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Register</h3>
      <div class="flex">
         <div class="col">
            <p>Your Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter Your Name" maxlength="50" required class="box">
            <p>Your Profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- Select Your Profession</option>
               <option value="Assistant Professor">Assistant Professor</option>
               <option value="Data Engineer">Data Engineer</option>
               <option value="Backend Developer">Backend Developer</option>
               <option value="UI/UX Designer">UI/UX Designer</option>
               <option value="Head of Department">Head of Department</option>
               <option value="Frontend Developer">Frontend Developer</option>
               <option value="Android Developer">Android Developer</option>
                 <option value="C programmer">C programmer</option>
               <option value="web  series">web  series</option>
               <option value="youtuber">youtuber</option>
               <option value="Lecturer">Lecturer</option>
               <option value="Full Stack Developer">Full Stack Developer</option>
               <option value="Entrepreneur">Entrepreneur</option>
            </select>
            <p>Your Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter Your Email" maxlength="100" required class="box">
         </div>
         <div class="col">
            <p>Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter Your Password" maxlength="50" required class="box">
            <p>Confirm Password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm Your Password" maxlength="50" required class="box">
            <p>Select Profile Picture <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">Already have an account? <a href="login.php">Login Now</a></p>
      <input type="submit" name="submit" value="Register Now" class="btn">
   </form>
</section>

<script>
let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enableDarkMode = () => {
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
};

const disableDarkMode = () => {
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
};

if (darkMode === 'enabled') {
   enableDarkMode();
} else {
   disableDarkMode();
}
</script>

</body>
</html>
