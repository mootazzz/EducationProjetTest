<?php
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php';

$evaluationController = new EvaluationController();
$idEvaluation = $_GET['id']; // Récupérer l'ID de l'évaluation
$questions = $evaluationController->listQuestionsByEvaluation($idEvaluation);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions for Evaluation</title>
    <style>
        /* Styles globaux */
        body {
            background: linear-gradient(135deg, #0052cc, #66b2ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
            color: #ffffff;
            margin: 0;
        }

        /* Conteneur principal */
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1200px;
            position: relative;
        }

        h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #0052cc;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #1a73e8;
            color: #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        td {
            font-size: 14px;
            color: #555;
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f5fe;
        }

        /* Message en cas d'absence de données */
        .no-data {
            text-align: center;
            color: #d9534f;
            font-size: 18px;
            margin: 20px 0;
        }

        /* Pied de page */
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>  Questions For This Evaluation   </h1>
        <?php if (!empty($questions)): ?>
            <table>
                <thead>
                    <tr>
                        <?php 
                        // Afficher les en-têtes (les clés des questions)
                        if (!empty($questions[0])) {
                            foreach (array_keys($questions[0]) as $key) {
                                echo "<th>" . htmlspecialchars($key) . "</th>";
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                        <tr>
                            <?php foreach ($question as $value): ?>
                                <td><?= htmlspecialchars($value) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No questions found </p>
        <?php endif; ?>
    </div>

</body>
</html>