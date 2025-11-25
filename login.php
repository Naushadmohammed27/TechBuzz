<?php
include 'components/connect.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
$message = [];

if (isset($_POST['submit'])) {
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $password = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $password]);

   if ($select_user->rowCount() > 0) {
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      // 🔥 FIXED COOKIE — correct path "/TechBuzz/"
      setcookie(
         'user_id',
         $row['id'],
         time() + (86400 * 30),           // 30 days
         "/TechBuzz/",                    // <---- IMPORTANT
         "localhost",
         false,                           // secure = false for localhost
         true                             // HttpOnly = true
      );

      header('Location: home.php');
      exit();
   } else {
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>TechBuzz - Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main2.css">
   <link rel="icon" href="images/favicon.png" type="image/x-icon">
</head>
<body>

<?php if (!empty($message)) {
   foreach ($message as $msg) {
      echo "<div class='message'><span>$msg</span><i class='fas fa-times' onclick='this.parentElement.remove();'></i></div>";
   }
} ?>

<nav class="nav">
   <section class="flex">
      <div class="main_logo">
         <i class="uil uil-airplay"></i>
         <a href="home.php" class="logo">TechBuzz</a>
      </div>

      <form action="search_course.php" method="post" class="search-form">
         <input type="text" name="search_course" placeholder="Search courses..." required>
         <button type="submit" class="fas fa-search" name="search_course_btn"></button>
      </form>

      <div class="navmenu">
         <a class="nav__link active-link" href="home.php">Home</a>
         <a class="nav__link" href="about.php">About</a>
         <a class="nav__link" href="courses.php">Courses</a>
         <a class="nav__link" href="teachers.php">Teachers</a>
         <a class="nav__link" href="contact.php">Contact us</a>
      </div>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="close-btn" class="fas fa-times"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
         if (!empty($user_id)) {
            $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
               echo '
               <img class="image" src="uploaded_files/' . $fetch_profile['image'] . '" alt="">
               <h3 class="prof">' . $fetch_profile['name'] . '</h3>
               <span class="student">Student</span>
               <a href="profile.php" class="btn" style="background-color: #16a085;">View profile</a>
               <a href="components/user_logout.php" onclick="return confirm(\'Logout from this website?\');" class="delete-btn">Logout</a>';
            }
         } else {
            echo '
            <h3>Please login or register</h3>
            <div class="flex-btn">
               <a href="login.php" class="option-btn">Login</a>
               <a href="register.php" class="option-btn">Register</a>
            </div>';
         }
         ?>
      </div>
   </section>
</nav>

<section class="form-container">
   <div class="welcome">
      <h1>Hello, Again!</h1>
      <p>Reconnect with your dashboard and resume your learning journey. Login now to continue!</p>
      <img src="images/books.png" alt="Books">
   </div>

   <form method="post" class="login" enctype="multipart/form-data">
      <h3>User Login</h3>
      <div class="form-box">
         <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
         <i class="uil uil-envelope"></i>
      </div>
      <div class="form-box">
         <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
         <i class="uil uil-padlock"></i>
      </div>
      <label><input type="checkbox"> Remember me</label>
      <input type="submit" name="submit" value="Login now" class="btnn btn">
      <p class="link">Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

<script src="js/app.js"></script>
</body>
</html>
