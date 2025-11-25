<?php
require_once __DIR__ . '/../components/connect.php';

$cert_id = $_GET['id'] ?? '';

if ($cert_id == '') {
    echo "<h2 style='color:red;'>Invalid Certificate ID</h2>";
    exit;
}

$select = $conn->prepare("SELECT * FROM certificates WHERE certificate_id = ? LIMIT 1");
$select->execute([$cert_id]);

if ($select->rowCount() == 0) {
    echo "<h2 style='color:red;'>❌ Certificate Not Found</h2>";
    echo "<p>This certificate ID does not exist or has been deleted.</p>";
    exit;
}

$cert = $select->fetch(PDO::FETCH_ASSOC);

// Fetch user for additional info (optional)
$get_user = $conn->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
$get_user->execute([$cert['user_id']]);
$user = $get_user->fetch(PDO::FETCH_ASSOC);

// Fetch playlist title
$get_playlist = $conn->prepare("SELECT title FROM playlist WHERE id = ? LIMIT 1");
$get_playlist->execute([$cert['playlist_id']]);
$playlist = $get_playlist->fetch(PDO::FETCH_ASSOC);

// Output Certificate Verification Page
echo "<h2 style='color:green;'>✔ Certificate Verified</h2>";

echo "<p><strong>Certificate ID:</strong> " . $cert['certificate_id'] . "</p>";
echo "<p><strong>Name:</strong> " . htmlspecialchars($user['name']) . "</p>";
echo "<p><strong>Course:</strong> " . htmlspecialchars($playlist['title']) . "</p>";
echo "<p><strong>Issued Date:</strong> " . $cert['issued_date'] . "</p>";

echo "<br><a href='../certificate/generate_certificate.php?user_id=".$cert['user_id']."&playlist_id=".$cert['playlist_id']."' 
        style='padding:10px 15px; background:#1abc9c; color:white; text-decoration:none; border-radius:5px;'>
        Download Certificate Again
      </a>";
?>
