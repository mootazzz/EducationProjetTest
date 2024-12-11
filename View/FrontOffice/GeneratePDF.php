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
$query = "SELECT titre, description,type, date_limite, duree FROM evaluation";
$stmt = $pdo->query($query);
$evaluation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// En-tête du tableau
$pdf->Cell(40, 10, 'Title', 1);
$pdf->Cell(50, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Type', 1);
$pdf->Cell(35, 10, 'Deadline', 1);
$pdf->Cell(30, 10, 'Duration', 1);

$pdf->Ln();

// Contenu du tableau
foreach ($evaluation as $evaluations) {
    $pdf->Cell(40, 15, $evaluations['titre'], 1);
    $pdf->Cell(50, 15, $evaluations['description'], 1);
    $pdf->Cell(30, 15, $evaluations['type'], 1);
    
    $pdf->Cell(35, 15, $evaluations['date_limite'], 1);
    $pdf->Cell(30, 15, $evaluations['duree'], 1);
    
    $pdf->Ln();
}

// Générer le fichier PDF
$pdf->Output('D', 'evaluations.pdf');
?>