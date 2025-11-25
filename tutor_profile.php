<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_STRING);
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE email = ?');
   $select_tutor->execute([$tutor_email]);

   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
   $tutor_id = $fetch_tutor['id'];

   // counts
   $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
   $count_playlists->execute([$tutor_id]);
   $total_playlists = $count_playlists->rowCount();

   $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
   $count_likes->execute([$tutor_id]);
   $total_likes = $count_likes->rowCount();

   $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
   $count_comments->execute([$tutor_id]);
   $total_comments = $count_comments->rowCount();

   $count_live_classes = $conn->prepare("SELECT * FROM `live_classes` WHERE tutor_id = ?");
   $count_live_classes->execute([$tutor_id]);
   $total_live_classes = $count_live_classes->rowCount();

}else{
   header('location:teachers.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor's Profile</title>

   <!-- Font Awesome + Unicons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="icon" href="images/favicon.png" type="image/x-icon">

   <style>
      .about_info {
         display: flex;
         flex-wrap: wrap;
         gap: 1.2rem;
         margin-top: 1.5rem;
      }
      .about_box {
         flex: 1 1 220px;
         background: #e6e4ff;
         border-radius: 12px;
         box-shadow: 4px 4px 8px rgba(0,0,0,0.2);
         text-align: center;
         padding: 1rem 0.5rem;
         transition: all 0.3s ease;
      }
      .about_box:hover { transform: translateY(-5px); }
      .about_flex {
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 10px;
         margin-bottom: 0.5rem;
      }
      .about_icon {
         display: flex;
         align-items: center;
         justify-content: center;
         background: #ff7675;
         color: #fff;
         width: 35px;
         height: 35px;
         border-radius: 8px;
         font-size: 18px;
      }
      .about_count {
         font-size: 0.95rem;
         color: #222;
         font-weight: 500;
      }
      .about_box h3 {
         font-size: 1.2rem;
         color: #000;
      }
      .courses {
         margin-top: 3rem;
      }
      .course_image img {
         width: 100%;
         height: 200px;
         object-fit: cover;
         border-radius: 10px;
      }
      .course_image span {
         position: absolute;
         bottom: 10px;
         left: 10px;
         background: rgba(0, 0, 0, 0.7);
         color: #fff;
         padding: 5px 10px;
         border-radius: 6px;
         font-size: 0.9rem;
      }
      .course {
         position: relative;
         overflow: hidden;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="home">
   <div class="container">
      <div class="home-info">
         <div class="left">
            <h3>Hello, I'm</h3>
            <h1><?= htmlspecialchars($fetch_tutor['name']); ?></h1>
            <h4>And I'm a <span class="multiple"><?= htmlspecialchars($fetch_tutor['profession']); ?></span></h4>
            <p>🚀 As your dedicated teacher, I'm thrilled to embark on this learning journey with you through TechBuzz. Let's make every lesson a discovery and every challenge a triumph! 🚀🔍</p>
            
            <div class="about_info">
               <div class="about_box">
                  <div class="about_flex">
                     <i style="background:#ff7675" class="about_icon fas fa-heart"></i>
                     <h3><?= $total_likes; ?></h3>
                  </div>
                  <div class="about_count">Likes received</div>
               </div>

               <div class="about_box">
                  <div class="about_flex">
                     <i style="background:#f9ca24" class="about_icon fas fa-comment"></i>
                     <h3><?= $total_comments; ?></h3>
                  </div>
                  <div class="about_count">Comments received</div>
               </div>

               <div class="about_box">
                  <div class="about_flex">
                     <i style="background:#00b894" class="about_icon fas fa-bookmark"></i>
                     <h3><?= $total_playlists; ?></h3>
                  </div>
                  <div class="about_count">Created playlists</div>
               </div>

               <div class="about_box">
                  <div class="about_flex">
                     <i style="background:#0984e3" class="about_icon fas fa-video"></i>
                     <h3><?= $total_live_classes; ?></h3>
                  </div>
                  <div class="about_count">Live classes hosted</div>
               </div>
            </div>
         </div> 

         <div class="right">
            <div class="profile">
               <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="">
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Uploaded Playlists -->
<section class="courses">
   <h1 class="heading">Uploaded Playlists</h1>
   <div class="container search-box-container">
      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = ?");
         $select_courses->execute([$tutor_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];
               $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
               $count_videos->execute([$course_id]);
               $total_videos = $count_videos->rowCount();
      ?>
      <article class="course">
         <div class="tutor">
            <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="">
            <div class="info">
               <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
               <span><?= htmlspecialchars($fetch_course['date']); ?></span>
            </div>
         </div>
         <div class="course_image">
            <img src="uploaded_files/<?= htmlspecialchars($fetch_course['thumb']); ?>" class="thumb" alt="">
            <span><?= $total_videos; ?> videos</span>
         </div>
         <div class="course_info">
            <h4 class="title"><?= htmlspecialchars($fetch_course['title']); ?></h4>
            <p class="description"><?= htmlspecialchars($fetch_course['description']); ?></p>
            <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn" style="background-color: #f75842;">View Playlist</a>
         </div>
      </article>
      <?php
            }
         }else{
            echo '<p class="empty">No courses added yet!</p>';
         }
      ?>
   </div>
</section>

<!-- Live Classes -->
<section class="courses">
   <h1 class="heading">Live Classes</h1>
   <div class="container search-box-container">
      <?php
         $select_live = $conn->prepare("SELECT * FROM `live_classes` WHERE tutor_id = ? ORDER BY date_time DESC");
         $select_live->execute([$tutor_id]);
         if($select_live->rowCount() > 0){
            while($live = $select_live->fetch(PDO::FETCH_ASSOC)){
               // fallback image logic
               $live_image = (!empty($live['thumb']) && file_exists("uploaded_files/".$live['thumb'])) 
                  ? "uploaded_files/".$live['thumb'] 
                  : "https://img.freepik.com/free-vector/live-streaming-banner-modern-style_1017-31184.jpg";
      ?>
      <article class="course">
         <div class="course_image">
            <img src="<?= $live_image; ?>" alt="Live Class">
            <span><?= date('d M, h:i A', strtotime($live['date_time'])); ?></span>
         </div>
         <div class="course_info">
            <h4 class="title"><?= htmlspecialchars($live['title']); ?></h4>
            <p class="description"><?= htmlspecialchars($live['description']); ?></p>
            <a href="<?= htmlspecialchars($live['class_link']); ?>" target="_blank" class="inline-btn" style="background-color:#0984e3;">Join Live</a>
         </div>
      </article>
      <?php
            }
         } else {
            echo '<p class="empty">No live classes scheduled yet!</p>';
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/app.js"></script>
<script>
   document.querySelectorAll('.description').forEach(content => {
      if(content.innerHTML.length > 120)
         content.innerHTML = content.innerHTML.slice(0, 180) + '...';
   });
</script>
</body>
</html>
