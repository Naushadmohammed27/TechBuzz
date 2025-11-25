<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}

// Capture search query
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Courses | TechBuzz</title>

   <link rel="icon" href="images/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">

   <style>
      .search-section {
         text-align: center;
         margin: 40px auto;
         width: 100%;
      }

      .search-bar {
         display: inline-flex;
         align-items: center;
         background: #fff;
         border-radius: 50px;
         border: 1.5px solid #ddd;
         box-shadow: 0 4px 15px rgba(0,0,0,0.08);
         overflow: hidden;
         transition: all 0.3s ease;
      }

      .search-bar:hover, .search-bar:focus-within {
         box-shadow: 0 6px 20px rgba(0,0,0,0.12);
         border-color: #666;
      }

      .search-bar input {
         border: none;
         outline: none;
         padding: 12px 18px;
         width: 280px;
         font-size: 16px;
         font-family: 'Poppins', sans-serif;
         color: #333;
         background: transparent;
      }

      .search-bar button {
         background: linear-gradient(135deg, #0078ff, #00c6ff);
         border: none;
         color: #fff;
         padding: 12px 24px;
         cursor: pointer;
         font-size: 16px;
         font-weight: 600;
         transition: all 0.3s ease;
      }

      .search-bar button:hover {
         background: linear-gradient(135deg, #006ae3, #00a7e1);
         transform: scale(1.03);
      }

      .section-heading {
         font-size: 2.8rem;
         font-weight: 700;
         text-align: center;
         margin-bottom: 30px;
         color: #222;
         letter-spacing: 0.5px;
      }

      .empty {
         text-align: center;
         color: #999;
         font-size: 1.2rem;
         padding: 40px 0;
      }

      .description {
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
         -webkit-line-clamp: 5;
         -webkit-box-orient: vertical;
      }

      @media (max-width: 768px) {
         .search-bar input {
            width: 180px;
         }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- 🔍 Search Section -->
<section class="search-section">
   <form method="GET" action="" class="search-bar">
      <input type="text" name="search" placeholder="Search for courses..." value="<?= htmlspecialchars($search_query); ?>">
      <button type="submit"><i class="fa fa-search"></i> Search</button>
   </form>
</section>

<!-- 📚 Courses Section -->
<section class="courses" id="courses" style="margin-top: 10px;">
   <h1 class="section-heading">Explore Our Courses</h1>

   <div class="container box-container">
      <?php
      // Array of static courses
      $static_courses = [
         [
            'title' => 'Frontend Development',
            'image' => 'images/web.jpg',
            'demo'  => 'demo5.php',
            'description' => 'Frontend development focuses on creating the user interface and user experience of a website or application. It involves implementing designs using HTML, CSS, and JavaScript...'
         ],
         [
            'title' => 'Backend Development',
            'image' => 'images/course17.jpg',
            'demo'  => 'demo4.php',
            'description' => 'Backend development involves creating and maintaining server-side logic, databases, and APIs Backend development involves creating and maintainingBackend development involves...'
         ],
         [
            'title' => 'Android Developers',
            'image' => 'images/android 12.jpg',
            'demo'  => 'demo1.php',
            'description' => 'An Android development course equips participants with essential skills to create mobile applications for Android devices Android development course equips participants with ...'
         ],
         [
            'title' => 'DevOps',
            'image' => 'images/course20.jpg',
            'demo'  => 'demo6.php',
            'description' => 'DevOps improves collaboration between development and operations teams using automation, CI/CD, and tools like Docker, Jenkins, and Kubernetes DevOps improves collaboration betwe...'
         ]
      ];

      // Filter static courses based on search
      $filtered_static = [];
      foreach ($static_courses as $course) {
         if (empty($search_query) || stripos($course['title'], $search_query) !== false || stripos($course['description'], $search_query) !== false) {
            $filtered_static[] = $course;
         }
      }

      if (count($filtered_static) > 0) {
         foreach ($filtered_static as $course) {
            ?>
            <article class="course">
               <div class="tutor">
                  <img src="images/fevicon.png" alt="">
                  <div class="info">
                     <h3>TechBuzz</h3>
                  </div>
               </div>
               <div class="course_image">
                  <a href="<?= $course['demo']; ?>">
                     <img src="<?= $course['image']; ?>" alt="" style="cursor:pointer;">
                  </a>
               </div>
               <div class="course_info">
                  <h4><?= htmlspecialchars($course['title']); ?></h4>
                  <p class="description"><?= htmlspecialchars($course['description']); ?></p>
                  <a href="<?= $course['demo']; ?>" class="main_btn inline-btn">Watch Demo</a>
               </div>
            </article>
            <?php
         }
      } else {
         echo '<p class="empty">No matching courses found!</p>';
      }
      ?>
   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn" style="background-color: rgb(37,17,189); border-radius: 10px;">View All Courses</a>
   </div>
</section>

<!-- 📂 Expert Uploaded Courses -->
<section class="courses" style="margin-top: 50px;">
   <h1 class="section-heading">Uploaded Courses by Our Experts</h1>
   <div class="container box-container">
      <?php
      // Search filter for expert uploaded courses
      if (!empty($search_query)) {
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND (title LIKE ? OR description LIKE ?) ORDER BY date DESC");
         $search_term = "%$search_query%";
         $select_courses->execute(['active', $search_term, $search_term]);
      } else {
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
         $select_courses->execute(['active']);
      }

      if ($select_courses->rowCount() > 0) {
         while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
            $course_id = $fetch_course['id'];

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$fetch_course['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$course_id]);
            $total_videos = $count_videos->rowCount();
            ?>
            <article class="course">
               <div class="tutor">
                  <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="<?= $fetch_tutor['name']; ?>">
                  <div class="info">
                     <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
                     <span><?= htmlspecialchars($fetch_course['date']); ?></span>
                  </div>
               </div>
               <div class="course_image">
                  <a href="playlist.php?get_id=<?= $course_id; ?>">
                     <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="<?= htmlspecialchars($fetch_course['title']); ?>" style="cursor:pointer;">
                  </a>
                  <span><?= $total_videos; ?> videos</span>
               </div>
               <div class="course_info">
                  <h4 class="title"><?= htmlspecialchars($fetch_course['title']); ?></h4>
                  <p class="description"><?= htmlspecialchars($fetch_course['description']); ?></p>
                  <a href="playlist.php?get_id=<?= $course_id; ?>" class="main_btn inline-btn">View Playlist</a>
               </div>
            </article>
            <?php
         }
      } else {
         echo '<p class="empty">No matching expert courses found!</p>';
      }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/app.js"></script>
<script>
document.querySelectorAll('.description').forEach(content => {
   if (content.textContent.length > 220) {
      content.textContent = content.textContent.slice(0, 220) + '...';
   }
});
</script>

</body>
</html>
