<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {}
}

// ---------------- INPUTS ----------------
$user_name   = isset($_GET['user']) ? strtoupper($_GET['user']) : "MAHESH BABU";
$course_name = isset($_GET['course']) ? $_GET['course'] : "Data Engineer";
$date_value  = date("d M Y");

// --------------- FILE PATH ---------------
$templatePath = __DIR__ . "/templates/certificate_bg.png";  // your uploaded template

// ------------- CREATE PDF ----------------
$pdf = new PDF();
$pdf->AddPage('L', 'A4');

// -------------- BACKGROUND ----------------
$pdf->Image($templatePath, 0, 0, 297);   // full A4 landscape

// ---------------- TEXT COLOR -------------
$pdf->SetTextColor(0, 0, 0);


// ---------------------------------------------------------
//            PLACE TEXT EXACTLY ON YOUR TEMPLATE
// ---------------------------------------------------------

// USER NAME (center)
$pdf->SetFont('Arial', 'B', 38);
$pdf->SetXY(0, 105);
$pdf->Cell(297, 10, $user_name, 0, 1, 'C');

// COURSE NAME (center)
$pdf->SetFont('Arial', 'B', 26);
$pdf->SetXY(0, 145);
$pdf->Cell(297, 10, $course_name, 0, 1, 'C');

// DATE (center)
$pdf->SetFont('Arial', '', 20);
$pdf->SetXY(0, 165);
$pdf->Cell(297, 10, "Date: " . $date_value, 0, 1, 'C');


// ---------------- OUTPUT PDF ----------------
$pdf->Output("I", "certificate.pdf");
?>
