<?php
include '../../controller/QuestionController.php'; // Adapter le chemin selon votre structure
$error = ""; // Pour afficher les erreurs si nécessaire

// Vérification si le formulaire est soumis
if (
    isset($_POST["contenu"]) && isset($_POST["type"]) &&
    isset($_POST["options"]) && isset($_POST["bonneReponse"]) &&
    isset($_POST["points"])
) {
    // Vérification que tous les champs sont remplis
    if (
        !empty($_POST["contenu"]) && !empty($_POST["type"]) &&
        !empty($_POST["bonneReponse"]) && !empty($_POST["points"])
    ) {
        // Préparer les données
        $contenu = $_POST["contenu"];
        $type = $_POST["type"];
        $options = !empty($_POST["options"]) ? explode(',', $_POST["options"]) : []; // Les options, séparées par des virgules
        $bonneReponse = $_POST["bonneReponse"];
        $points = (int)$_POST["points"];

        // Créer une nouvelle instance de Question
        $question = new Question(
            null, // L'ID sera généré automatiquement
            $contenu,
            $type,
            $options,
            $bonneReponse,
            $points
        );

        // Appeler le contrôleur pour ajouter la question
        $questionController = new QuestionController();
        $questionController->addQuestion($question);

        // Redirection ou message de succès
        header('Location: listQuestion.php'); // Modifier selon votre structure
        exit;
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .form-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        .form-container h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #0052cc;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #0052cc;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #003d99;
        }
        .error-message {
            color: #ff0000;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Question</h1>

        <!-- Formulaire -->
        <form id="questionForm" action="addQuestion.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="contenu">Contenu de la Question</label>
                <textarea class="form-control" name="contenu" id="contenu" placeholder="Entrez le contenu de la question" required></textarea>
                <div id="error-contenu" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="type">Type de Question</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="" disabled selected>-- Sélectionnez un type --</option>
                    <option value="choix_multiples">Choix Multiples</option>
                    <option value="vrai_faux">Vrai/Faux</option>
                    <option value="reponse_courte">Réponse Courte</option>
                </select>
                <div id="error-type" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="options">Options (séparées par des virgules)</label>
                <input type="text" class="form-control" name="options" id="options" placeholder="Option1,Option2,Option3">
            </div>

            <div class="form-group">
                <label for="bonneReponse">Bonne Réponse</label>
                <input type="text" class="form-control" name="bonneReponse" id="bonneReponse" placeholder="Entrez la bonne réponse" required>
                <div id="error-bonneReponse" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="points">Points</label>
                <input type="number" class="form-control" name="points" id="points" placeholder="Nombre de points" min="1" required>
                <div id="error-points" class="error-message"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(e => e.textContent = '');

            // Validate content
            const contenu = document.getElementById('contenu');
            if (contenu.value.trim() === '') {
                document.getElementById('error-contenu').textContent = 'Le contenu de la question est requis.';
                isValid = false;
            }

            // Validate type
            const type = document.getElementById('type');
            if (type.value === '') {
                document.getElementById('error-type').textContent = 'Veuillez sélectionner un type.';
                isValid = false;
            }

            // Validate correct answer
            const bonneReponse = document.getElementById('bonneReponse');
            if (bonneReponse.value.trim() === '') {
                document.getElementById('error-bonneReponse').textContent = 'La bonne réponse est requise.';
                isValid = false;
            }

            // Validate points
            const points = document.getElementById('points');
            if (points.value <= 0) {
                document.getElementById('error-points').textContent = 'Les points doivent être supérieurs à 0.';
                isValid = false;
            }

            return isValid; // Si la validation échoue, l'envoi du formulaire sera empêché
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
