<?php
require('C:\xampp\htdocs\EducationProjetTest - testlekher\View\pdf\fpdf.php');

// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bd1";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les questions
$query = "SELECT idQuestion, contenu, type, options, bonne_reponse, points, idEvaluation FROM questions";
$stmt = $pdo->query($query);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// En-tête du tableau
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Contenu', 1);
$pdf->Cell(20, 10, 'Type', 1);
$pdf->Cell(50, 10, 'Options', 1);
$pdf->Cell(30, 10, 'bonne_reponse', 1);
$pdf->Cell(20, 10, 'Points', 1);
$pdf->Cell(30, 10, 'ID Evaluation', 1);
$pdf->Ln();

// Contenu du tableau
foreach ($questions as $question) {
    $pdf->Cell(20, 10, $question['idQuestion'], 1);
    $pdf->Cell(50, 10, $question['contenu'], 1);
    $pdf->Cell(20, 10, $question['type'], 1);
    $options = json_decode($question['options'], true);
    $pdf->Cell(30, 10, $question['bonne_reponse'], 1);
    $pdf->Cell(20, 10, $question['points'], 1);
    $pdf->Cell(30, 10, $question['idEvaluation'], 1);
    $pdf->Ln();
}

// Générer le fichier PDF
$pdf->Output('D', 'questions.pdf');
?>