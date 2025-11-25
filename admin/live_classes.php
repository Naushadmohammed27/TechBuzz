<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Add new live class
if(isset($_POST['add_class'])){
   $title = htmlspecialchars($_POST['title']);
   $description = htmlspecialchars($_POST['description']);
   $class_link = htmlspecialchars($_POST['class_link']);
   $date_time = $_POST['date_time'];

   $insert = $conn->prepare("INSERT INTO `live_classes` (tutor_id, title, description, class_link, date_time) VALUES (?,?,?,?,?)");
   $insert->execute([$tutor_id, $title, $description, $class_link, $date_time]);
   $message[] = 'New live class added successfully!';
}

// Delete class
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete = $conn->prepare("DELETE FROM `live_classes` WHERE id = ? AND tutor_id = ?");
   $delete->execute([$delete_id, $tutor_id]);
   header('location:live_classes.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Live Classes</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="icon" href="../images/favicon.png" type="image/x-icon">

   <style>
      .live-classes-container {
         max-width: 1100px;
         margin: 0 auto;
         padding: 2rem;
      }

      .add-class-form {
         background: #fff;
         padding: 2rem;
         border-radius: 10px;
         box-shadow: 0 2px 10px rgba(0,0,0,0.1);
         margin-bottom: 2rem;
      }

      .add-class-form h2 {
         margin-bottom: 1.5rem;
         color: #222;
         font-size: 1.6rem;
      }

      .add-class-form input,
      .add-class-form textarea {
         width: 100%;
         padding: 10px;
         margin: 0.5rem 0 1rem;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 1rem;
      }

      .add-class-form .btn {
         background-color: #0984e3;
         color: white;
         border: none;
         padding: 10px 20px;
         border-radius: 5px;
         cursor: pointer;
         transition: 0.3s;
      }

      .add-class-form .btn:hover {
         background-color: #086cd3;
      }

      .classes-list {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 1.5rem;
      }

      .class-card {
         background: #fff;
         padding: 1.5rem;
         border-radius: 10px;
         box-shadow: 0 2px 10px rgba(0,0,0,0.1);
         transition: transform 0.2s ease;
      }

      .class-card:hover {
         transform: translateY(-5px);
      }

      .class-card h3 {
         color: #333;
         margin-bottom: 0.5rem;
         font-size: 1.2rem;
      }

      .class-card p {
         color: #555;
         font-size: 0.95rem;
         margin-bottom: 0.5rem;
      }

      .class-card .date {
         font-size: 0.9rem;
         color: #777;
         margin-bottom: 1rem;
      }

      .class-card .btn {
         display: inline-block;
         margin-right: 10px;
         background: #00b894;
         color: #fff;
         padding: 8px 14px;
         border-radius: 5px;
         text-decoration: none;
         transition: background 0.3s;
      }

      .class-card .btn:hover {
         background: #019870;
      }

      .class-card .delete-btn {
         background: #d63031;
      }

      .class-card .delete-btn:hover {
         background: #c0392b;
      }

      .empty {
         text-align: center;
         font-size: 1rem;
         color: #777;
         padding: 2rem 0;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="live-classes-container">
   
   <div class="add-class-form">
      <h2><i class="fa-solid fa-plus"></i> Add New Live Class</h2>
      <form action="" method="post">
         <label>Title</label>
         <input type="text" name="title" required placeholder="Enter class title">

         <label>Description</label>
         <textarea name="description" placeholder="Write a short description"></textarea>

         <label>Class Link</label>
         <input type="url" name="class_link" required placeholder="Paste live meeting link (Zoom/Meet/etc.)">

         <label>Date & Time</label>
         <input type="datetime-local" name="date_time" required>

         <button type="submit" name="add_class" class="btn">Add Class</button>
      </form>
   </div>

   <h2 style="margin-bottom:1rem;"><i class="fa-solid fa-chalkboard-user"></i> Your Scheduled Classes</h2>

   <div class="classes-list">
      <?php
         $select_classes = $conn->prepare("SELECT * FROM `live_classes` WHERE tutor_id = ? ORDER BY date_time DESC");
         $select_classes->execute([$tutor_id]);
         if($select_classes->rowCount() > 0){
            while($fetch = $select_classes->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="class-card">
         <h3><?= htmlspecialchars($fetch['title']); ?></h3>
         <p><?= nl2br(htmlspecialchars($fetch['description'])); ?></p>
         <p class="date"><i class="fa-regular fa-calendar"></i> <?= date('d M Y, h:i A', strtotime($fetch['date_time'])); ?></p>
         <a href="<?= htmlspecialchars($fetch['class_link']); ?>" target="_blank" class="btn"><i class="fa-solid fa-play"></i> Join</a>
         <a href="live_classes.php?delete=<?= $fetch['id']; ?>" class="btn delete-btn" onclick="return confirm('Delete this class?');"><i class="fa-solid fa-trash"></i> Delete</a>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No live classes scheduled yet. Add your first one above!</p>';
         }
      ?>
   </div>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
