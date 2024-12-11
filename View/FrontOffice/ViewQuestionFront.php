<?php
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php';
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\config.php';  // Inclure la connexion à la base de données

$evaluationController = new EvaluationController();
$idEvaluation = isset($_GET['id']) ? $_GET['id'] : 0; // Récupérer l'ID ou 0 si non présent

if ($idEvaluation == 0) {
    echo "L'ID de l'évaluation est invalide ou manquant.";
    exit;
}

$questions = $evaluationController->listQuestionsByEvaluation($idEvaluation);

// Table for storing results
$results = [];
$score = 0; // Variable pour le score total

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bd1');  // Remplacez avec vos informations de connexion

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $results = []; // Initialize the results array to store the questions and their status

    foreach ($_POST as $questionIndex => $reponse) {
        // Vérifier que la réponse n'est pas vide
        if (!empty($reponse) && strpos($questionIndex, 'question_') === 0) {
            // Extraire l'ID de la question à partir du nom du champ
            $questionId = str_replace('question_', '', $questionIndex);

            // Récupérer la bonne réponse de la base de données
            $query = "SELECT contenu, bonne_reponse, points FROM questions WHERE idQuestion = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
            $result = $stmt->get_result();
            $question = $result->fetch_assoc();

            if ($question) {
                // Comparer la réponse de l'utilisateur avec la bonne réponse
                $isCorrect = ($reponse === $question['bonne_reponse']) ? "Correct" : "Incorrect";
                if ($isCorrect === "Correct") {
                    $score += $question['points']; // Ajouter les points si la réponse est correcte
                }

                // Ajouter la question et le statut sans afficher l'ID de la question
                $results[] = ['questionContent' => $question['contenu'], 'status' => $isCorrect];

                // Enregistrer la réponse dans la table 'reponses'
                $stmt = $conn->prepare("INSERT INTO reponses (idEvaluation, idQuestion, reponse) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $idEvaluation, $questionId, $reponse);
                $stmt->execute();
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre aux Questions</title>
    <style>
        /* Styles globaux */
        body {
            background: linear-gradient(135deg, rgb(69, 72, 154), rgb(36, 36, 36));
            font-family: 'Roboto', sans-serif;

            color: #333;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
        }

        h1 {
            font-size: 2rem;
            text-align: center;
            color: #4a69bb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: rgb(70, 73, 158);
            color: #ffffff;
            text-align: left;
            font-size: 16px;
        }

        td {
            background-color: #f9f9f9;
            font-size: 14px;
            color: #555;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover {
            background-color: #dfe6e9;
        }

        button {
            background-color: rgb(70, 73, 158);
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
        }

        button:hover {
            background-color: rgb(36, 36, 36);
        }

        .options label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Résultats : afficher "Correct" ou "Incorrect" à côté de chaque question */
        .correct {
            color: green;
        }

        .incorrect {
            color: red;
        }

        .score {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #4a69bb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Answer Questions</h1>
        
        


        
        <form method="POST" action="">
            <?php if (!empty($questions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Questions</th>
                            <th>Options</th>
                            <th>Points</th>
                            <th>Status</th> <!-- Colonne pour afficher Correct/Incorrect -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $index => $question): ?>
                            <tr>
                                <td><?= htmlspecialchars($question['contenu'] ?? '') ?></td>
                                <td>
                                    <div class="options">
                                        <?php 
                                        $options = explode(',', $question['options'] ?? '');
                                        foreach ($options as $option):
                                            $clean_option = str_replace(['{', '}', '[', ']','"', '(', ')'], '', trim($option)); 
                                            if ($clean_option != ""): ?>
                                                <label>
                                                    <input type="radio" name="question_<?= $question['idQuestion'] ?>" value="<?= htmlspecialchars($clean_option) ?>">
                                                    <?= htmlspecialchars($clean_option) ?>
                                                </label>
                                            <?php endif;
                                        endforeach; ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($question['points'] ?? '') ?></td>
                                

                                <!-- Affichage du statut (Correct ou Incorrect) -->
                                <td>
                                    <?php 
                                    // Vérifier si le statut de cette question a été défini dans $results
                                    $status = '';
                                    foreach ($results as $result) {
                                        if ($result['questionContent'] == $question['contenu']) {
                                            $status = $result['status'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <span class="<?= strtolower($status) ?>"><?= $status ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Submit</button>
                
                
            <?php else: ?>
                <p class="no-data">There is no questions for this evaluation</p>
            <?php endif; ?>
        </form>

        <!-- Affichage du score -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="score">
                Score total: <?= $score ?> / 
                <?php
                    // Calculer le total possible des points
                    $totalPoints = 0;
                    foreach ($questions as $question) {
                        $totalPoints += $question['points'];
                    }
                    echo $totalPoints;
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
