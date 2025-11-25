<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

// ---------------- LIKE CONTENT --------------------
if(isset($_POST['like_content'])){

   if($user_id != ''){

      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
      $select_likes->execute([$user_id, $content_id]);

      if($select_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
         $remove_likes->execute([$user_id, $content_id]);
         $message[] = 'removed from likes!';
      }else{
         $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, tutor_id, content_id) VALUES(?,?,?)");
         $insert_likes->execute([$user_id, $tutor_id, $content_id]);
         $message[] = 'added to likes!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

// ---------------- ADD COMMENT --------------------
if(isset($_POST['add_comment'])){

   if($user_id != ''){

      $id = unique_id();
      $comment_box = filter_var($_POST['comment_box'], FILTER_SANITIZE_STRING);
      $content_id = filter_var($_POST['content_id'], FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      if($select_content->rowCount() > 0){

         $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND tutor_id = ? AND comment = ?");
         $select_comment->execute([$content_id, $user_id, $tutor_id, $comment_box]);

         if($select_comment->rowCount() > 0){
            $message[] = 'comment already added!';
         }else{
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES(?,?,?,?,?)");
            $insert_comment->execute([$id, $content_id, $user_id, $tutor_id, $comment_box]);
            $message[] = 'new comment added!';
         }

      }else{
         $message[] = 'something went wrong!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

// ---------------- DELETE COMMENT --------------------
if(isset($_POST['delete_comment'])){

   $delete_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment already deleted!';
   }

}

// ---------------- UPDATE COMMENT --------------------
if(isset($_POST['update_now'])){

   $update_id = filter_var($_POST['update_id'], FILTER_SANITIZE_STRING);
   $update_box = filter_var($_POST['update_box'], FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
   $verify_comment->execute([$update_id, $update_box]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'comment already added!';
   }else{
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$update_box, $update_id]);
      $message[] = 'comment edited successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch video</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
   <link rel="icon" href="images/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">

   <style>
      .ai-btn-right {
         margin-left: auto;
         background:#1abc9c;
         color:white;
         border:none;
         padding:8px 16px;
         border-radius:6px;
         cursor:pointer;
         font-size:14px;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section style="margin: 1rem 0;" class="watch-video">

<?php
   $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
   $select_content->execute([$get_id, 'active']);
   $ip='images/no-content.svg';

   if($select_content->rowCount() > 0){
      while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){

         $content_id = $fetch_content['id'];

         $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
         $count_videos->execute([$fetch_content['playlist_id']]);

         $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
         $select_likes->execute([$content_id]);
         $total_likes = $select_likes->rowCount();

         $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
         $verify_likes->execute([$user_id, $content_id]);

         $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
         $select_tutor->execute([$fetch_content['tutor_id']]);
         $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
?>

<div class="video-details">

   <video id="courseVideo" 
          src="uploaded_files/<?= $fetch_content['video']; ?>" 
          class="video"
          poster="uploaded_files/<?= $fetch_content['thumb']; ?>" 
          controls></video>

   <h3 class="title"><?= $fetch_content['title']; ?></h3>

   <div class="tutor" style="display:flex; align-items:center; justify-content:space-between;">
      <div style="display:flex; align-items:center;">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_tutor['profession']; ?></span>
         </div>
      </div>

      <button id="generateSummaryBtn"
              data-content-id="<?= $fetch_content['id']; ?>"
              class="ai-btn-right">
         Generate Summary & Quiz
      </button>
   </div>

   <div class="info">
      <form action="" method="post" class="flex">
         <input type="hidden" name="content_id" value="<?= $content_id; ?>">

         <?php if($verify_likes->rowCount() > 0){ ?>
         <button type="submit" name="like_content"><i class="fas fa-heart"></i></button>
         <?php } else { ?>
         <button type="submit" name="like_content"><i class="far fa-heart"></i></button>
         <?php } ?>
      </form>

      <p class="count"><?= $total_likes; ?> likes</p>
      <p><i style="color:#1abc9c;" class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
      <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>">view playlist</a>
   </div>

   <div class="description">
      <p>Content Description: <?= $fetch_content['description']; ?></p>
   </div>

   <div id="summaryOutput" class="ai-container"></div>

   <form action="" method="post" class="add-comment">
      <input type="hidden" name="content_id" value="<?= $get_id; ?>">
      <textarea name="comment_box" required maxlength="1000" placeholder="write your comment..."></textarea>
      <input type="submit" value="add comment" name="add_comment" class="main_btn inline-btn">
   </form>

   <div class="show-comments show-comments1">

      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? ORDER BY DATE DESC");
         $select_comments->execute([$get_id]);

         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>

      <div class="box">
         <div class="user">
            <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>

         <p class="text"><?= $fetch_comment['comment']; ?></p>

      </div>

      <?php
            }
         } else {
            echo '<img class="empty-image" src="'.$ip.'" alt="">';
            echo '<p class="empty" style="color:red;background:white;">no comments added yet!</p>';
         }
      ?>
   </div>

</div>

<!-- FIXED CERTIFICATE PROGRESS TRACKING -->
<script>

    document.addEventListener("DOMContentLoaded", function () {

    const video = document.getElementById("courseVideo");
    if (!video) return;

    const userId = "<?= $user_id ?>";
    const playlistId = "<?= $fetch_content['playlist_id'] ?>";
    const contentId = "<?= $fetch_content['id'] ?>";
    const totalVideos = <?= $count_videos->rowCount() ?>;

    video.addEventListener("ended", function () {

        fetch("certificate/update_progress.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:
                "user_id=" + encodeURIComponent(userId) +
                "&playlist_id=" + encodeURIComponent(playlistId) +
                "&video_id=" + encodeURIComponent(contentId) +
                "&total_videos=" + encodeURIComponent(totalVideos)
        })

        .then(response => response.text())
        .then(result => {
            console.log("Progress Update:", result);

            try {
                const data = JSON.parse(result);
                if (data.is_completed) {
                    alert("🎉 Playlist completed! Certificate unlocked!");
                    location.reload();
                }
            } catch (e) {
                console.warn("Invalid JSON:", result);
            }
        })
        .catch(err => console.error("Error:", err));
    });
});
</script>

<?php
      }
   } else {
      echo '<p class="empty">no videos added yet!</p>';
   }
?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/app.js"></script>

</body>
</html>

