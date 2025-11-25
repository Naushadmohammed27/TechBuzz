<?php
require __DIR__ . "/fpdf/fpdf.php";      // FIXED FPDF PATH
include "../components/connect.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$user_id       = $_POST['user_id'] ?? '';
$playlist_id   = $_POST['playlist_id'] ?? '';
$video_id      = $_POST['video_id'] ?? '';
$total_videos  = $_POST['total_videos'] ?? '';

if ($user_id === '' || $playlist_id === '' || $video_id === '' || $total_videos === '') {
    echo json_encode(["error" => "Missing values"]);
    exit;
}

//
// ─── STEP 1: CHECK PROGRESS ─────────────────────────────────────────────
//

$check = $conn->prepare("
    SELECT * FROM certificate_progress 
    WHERE user_id = ? AND playlist_id = ?
");
$check->execute([$user_id, $playlist_id]);

if ($check->rowCount() > 0) {
    $row = $check->fetch(PDO::FETCH_ASSOC);
    $completed = $row['completed_videos'];
} else {
    $completed = 0;
    $insert = $conn->prepare("
        INSERT INTO certificate_progress(user_id, playlist_id, completed_videos, total_videos, is_completed)
        VALUES(?, ?, ?, ?, 0)
    ");
    $insert->execute([$user_id, $playlist_id, 0, $total_videos]);
}

//
// ─── STEP 2: UPDATE PROGRESS ───────────────────────────────────────────
//

$newCount = $completed + 1;
if ($newCount > $total_videos) {
    $newCount = $total_videos;
}

$update = $conn->prepare("
    UPDATE certificate_progress 
    SET completed_videos = ?
    WHERE user_id = ? AND playlist_id = ?
");
$update->execute([$newCount, $user_id, $playlist_id]);

$isCompleted = ($newCount == $total_videos) ? 1 : 0;

if ($isCompleted) {

    $finish = $conn->prepare("
        UPDATE certificate_progress 
        SET is_completed = 1 
        WHERE user_id = ? AND playlist_id = ?
    ");
    $finish->execute([$user_id, $playlist_id]);

    //
    // ─── STEP 3: GENERATE CERTIFICATE (ONLY ONCE) ───────────────────────
    //

    $existingCert = $conn->prepare("
        SELECT * FROM user_certificates 
        WHERE user_id = ? AND playlist_id = ?
    ");
    $existingCert->execute([$user_id, $playlist_id]);

    if ($existingCert->rowCount() == 0) {

        // Fetch user name
        $userStmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $userStmt->execute([$user_id]);
        $userName = $userStmt->fetchColumn();

        // Fetch playlist title
        $plStmt = $conn->prepare("SELECT title FROM playlist WHERE id = ?");
        $plStmt->execute([$playlist_id]);
        $playlistTitle = $plStmt->fetchColumn();

        //
        // ─── Generate PDF ─────────────────────────────────────────────
        //

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->SetFont("Arial", "B", 28);
        $pdf->Cell(0, 20, "Certificate of Completion", 0, 1, 'C');

        $pdf->SetFont("Arial", "", 16);
        $pdf->Cell(0, 15, "This certifies that", 0, 1, 'C');

        $pdf->SetFont("Arial", "B", 22);
        $pdf->Cell(0, 15, $userName, 0, 1, 'C');

        $pdf->SetFont("Arial", "", 16);
        $pdf->Cell(0, 12, "has successfully completed the playlist", 0, 1, 'C');

        $pdf->SetFont("Arial", "B", 20);
        $pdf->Cell(0, 15, $playlistTitle, 0, 1, 'C');

        $pdf->SetFont("Arial", "", 14);
        $pdf->Cell(0, 10, "Date: " . date("d M Y"), 0, 1, 'C');

        //
        // ─── SAVE FILE INSIDE /certificate/certificates/ ───────────────
        //

        $fileName = "certificate_user_{$user_id}_playlist_{$playlist_id}.pdf";
        $saveFolder = __DIR__ . "/certificates/";
        $savePath = $saveFolder . $fileName;

        if (!is_dir($saveFolder)) {
            mkdir($saveFolder, 0777, true);
        }

        $pdf->Output("F", $savePath);

        //
        // ─── SAVE IN DATABASE ──────────────────────────────────────────
        //

        $save = $conn->prepare("
            INSERT INTO user_certificates (user_id, playlist_id, certificate_path) 
            VALUES (?, ?, ?)
        ");
        $save->execute([$user_id, $playlist_id, "certificates/$fileName"]);
    }
}

//
// ─── FINAL RESPONSE ─────────────────────────────────────────────────────
//

echo json_encode([
    "success" => true,
    "completed_videos" => $newCount,
    "is_completed" => $isCompleted
]);
?>
