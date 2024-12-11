<?php
require('C:\xampp\htdocs\EducationProjetTest - testlekher\View\pdf\fpdf.php');
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php';

// Vérifier si l'ID est passé par POST
$idEvaluation = isset($_POST['id']) ? $_POST['id'] : 0;

if ($idEvaluation == 0) {
    die("Evaluation ID is missing or invalid.");
}

$evaluationController = new EvaluationController();
$questions = $evaluationController->listQuestionsByEvaluation($idEvaluation);

// Variables pour les résultats et le score
$results = [];
$score = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'bd1');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($_POST as $questionIndex => $reponse) {
        if (!empty($reponse) && strpos($questionIndex, 'question_') === 0) {
            $questionId = str_replace('question_', '', $questionIndex);
            $query = "SELECT contenu, bonne_reponse, points FROM questions WHERE idQuestion = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
            $result = $stmt->get_result();
            $question = $result->fetch_assoc();

            if ($question) {
                $isCorrect = ($reponse === $question['bonne_reponse']) ? "Correct" : "Incorrect";
                if ($isCorrect === "Correct") {
                    $score += $question['points'];
                }
                $results[] = ['questionContent' => $question['contenu'], 'status' => $isCorrect];
            }
        }
    }

    $conn->close();
}

// Générer le PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Titre
$pdf->Cell(200, 10, 'Evaluation Results', 0, 1, 'C');

// En-tête de tableau
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Question', 1, 0, 'C');
$pdf->Cell(40, 10, 'Answer Status', 1, 0, 'C');
$pdf->Cell(30, 10, 'Points', 1, 1, 'C');

// Réinitialiser la police pour les lignes du tableau
$pdf->SetFont('Arial', '', 12);

// Ajouter les questions et les réponses
$totalPoints = 0;
foreach ($results as $result) {
    $pdf->Cell(90, 10, $result['questionContent'], 1, 0, 'L');
    $pdf->Cell(40, 10, $result['status'], 1, 0, 'C');

    // Trouver les points de la question
    $questionId = array_search($result['questionContent'], array_column($questions, 'contenu'));
    $points = $questions[$questionId]['points'];
    $pdf->Cell(30, 10, $points, 1, 1, 'C');
    $totalPoints += $points;
}

// Afficher le score total
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(90, 10, 'Total Score:', 0, 0, 'L');
$pdf->Cell(40, 10, $score, 0, 0, 'C');
$pdf->Cell(30, 10, " / " . $totalPoints, 0, 1, 'C');

// Sortie du PDF
$pdf->Output();
?>
