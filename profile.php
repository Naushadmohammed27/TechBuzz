<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

// FETCH COUNTS
$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

// CERTIFICATE COUNT
$cert_count = $conn->prepare("SELECT COUNT(*) FROM user_certificates WHERE user_id = ?");
$cert_count->execute([$user_id]);
$total_certificates = $cert_count->fetchColumn();

// FETCH STUDENT PROFILE
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/main2.css">
   <link rel="icon" href="images/favicon.png" type="image/x-icon">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="container about sec" id="about">
   
   <div style="margin-top:1rem;" class="about_container con-about grid_about">
      <div class="about_image">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
      </div>
         
      <div class="about_data">
         <p style="font-size: 2.5rem; font-weight:bold;"> Hello! <span><?= $fetch_profile['name']; ?></span> ,</p>
         <p class="about_description">
            Hope you are doing well and making the most of your educational journey. Engage with the community by liking, commenting and sharing your thoughts on lessons and topics of interest. Never lose track of your progress - save bookmarks to revisit your favorite playlists easily. Thanks for being a part of our learning community and stay connected with fellow the experts.
         </p>

         <div class="about_info">

            <div class="about_box">
               <div class="about_flex">
                  <i class="about_icon fas fa-heart"></i>
                  <div><h3><?= $total_likes; ?></h3></div>
               </div>
               <div class="about_count">liked tutorials</div>
               <a href="likes.php" class="btn1">View</a>
            </div>

            <div class="about_box">
               <div class="about_flex">
                  <i style="background:#f9ca24" class="about_icon fas fa-comment"></i>
                  <div><h3><?= $total_comments; ?></h3></div>
               </div>
               <div class="about_count">comments</div>
               <a href="comments.php" class="btn1">View</a>
            </div>

            <div class="about_box">
               <div class="about_flex">
                  <i style="background:#00b894" class="about_icon fas fa-bookmark"></i>
                  <div><h3><?= $total_bookmarked; ?></h3></div>
               </div>
               <div class="about_count">saved tutorials</div>
               <a href="bookmark.php" class="btn1">View</a>
            </div>

            <!-- CERTIFICATE COUNT BOX -->
            <div class="about_box">
               <div class="about_flex">
                  <i class="about_icon fa-solid fa-trophy" style="color:#fff; background:#00b300;"></i>
                  <div><h3><?= $total_certificates ?></h3></div>
               </div>
               <div class="about_count">certificates</div>
               <a href="#certificates" class="btn1">View</a>
            </div>

         </div>

         <a style="background: #f75842" href="update.php" class="btn2 inline-btn">update profile</a>
      </div>
   </div>
</section>


<!-- ================= CERTIFICATES SECTION ================= -->
<section class="container" id="certificates" style="margin-top: 3rem;">
   <h2 class="heading">My Certificates</h2>

   <?php
   $cert = $conn->prepare("SELECT * FROM user_certificates WHERE user_id = ?");
   $cert->execute([$user_id]);

   if ($cert->rowCount() > 0) {
      echo '<div class="grid_about" style="gap:2rem;">';

      while ($c = $cert->fetch(PDO::FETCH_ASSOC)) {

         $playlistName = $conn->prepare("SELECT title FROM playlist WHERE id = ?");
         $playlistName->execute([$c['playlist_id']]);
         $plTitle = $playlistName->fetchColumn();
   ?>

         <div class="about_box" style="padding:2rem; text-align:center;">
            <i class="fa-solid fa-certificate" style="font-size:2.4rem; color:#f39c12;"></i>
            <h3 style="margin-top: 1rem;"><?= $plTitle ?></h3>
            <p style="font-size:1.3rem; margin:.5rem 0;">Completed on <?= $c['generated_on'] ?></p>
            <a href="certificate/<?= $c['certificate_path'] ?>" class="btn1" download>Download Certificate</a>
         </div>

   <?php
      }

      echo "</div>";
   } else {
      echo "<p style='margin-top:1rem; color:red; font-size:1.5rem;'>No certificates earned yet.</p>";
   }
   ?>
</section>


<?php include 'components/footer.php'; ?>  

<script src="js/app.js"></script>
</body>
</html>
