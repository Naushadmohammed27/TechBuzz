<?php

include 'components/connect.php';

$content_id = $_POST['content_id'] ?? '';

if (!$content_id) {
    echo json_encode(["error" => "Invalid content ID"]);
    exit;
}

// 🔥 Fetch FULL content details including description
$select = $conn->prepare("SELECT title, description FROM content WHERE id = ?");
$select->execute([$content_id]);
$video = $select->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    echo json_encode(["error" => "Content not found"]);
    exit;
}

$title = $video['title'];
$desc  = $video['description'];

// -----------------------------------------
//  AI LOGIC BASED ON DESCRIPTION
// -----------------------------------------

// ⭐ Auto Summary
$summary = "This video titled '$title' explains the topic in a simple and structured way. "
         . "It covers: $desc";

// ⭐ Auto Key Points (split description into meaningful points)
$raw_points = explode('.', $desc);
$key_points = [];

foreach ($raw_points as $point) {
    $clean = trim($point);
    if (strlen($clean) > 5) {
        $key_points[] = ucfirst($clean);
    }
}

// If no key points found, add safe fallback
if (count($key_points) == 0) {
    $key_points = [
        "The video gives a clear explanation of the topic.",
        "Important concepts are broken down into simple parts.",
        "Practical understanding is provided through examples."
    ];
}

// ⭐ Auto Quiz (generated from keywords)
$quiz = [
    "What is the main topic discussed in the video '$title'?",
    "Mention one key concept explained in the video.",
    "Based on the video, why is this topic important?",
    "What practical example or application did the video mention?",
    "Explain one point described in this video in your own words."
];

// -----------------------------------------
//  Save to database
// -----------------------------------------

$insert = $conn->prepare("
    INSERT INTO ai_summaries (content_id, summary, key_points, quiz_json)
    VALUES (?, ?, ?, ?)
");

$insert->execute([
    $content_id,
    $summary,
    json_encode($key_points),
    json_encode($quiz)
]);

// -----------------------------------------
//  Return JSON
// -----------------------------------------

echo json_encode([
    "summary"    => $summary,
    "key_points" => $key_points,
    "quiz"       => $quiz
]);

?>
