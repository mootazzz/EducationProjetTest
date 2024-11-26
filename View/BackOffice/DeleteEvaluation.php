<?php
include '../../controller/EvaluationController.php';

// Messages pour affichage
$errorMessage = "";
$successMessage = "";

// Traitement du formulaire de suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evaluationId'])) {
    $evaluationId = $_POST['evaluationId'];

    // Vérifier que l'ID est valide
    if (is_numeric($evaluationId)) {
        try {
            $evaluationController = new EvaluationController();
            $evaluationController->deleteEvaluation($evaluationId);

            $successMessage = "L'évaluation avec l'ID $evaluationId a été supprimée avec succès.";
        } catch (Exception $e) {
            $errorMessage = "Erreur lors de la suppression : " . $e->getMessage();
        }
    } else {
        $errorMessage = "ID invalide. Veuillez saisir un nombre.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete an Evaluation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3a8dff, #7c4dff);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            color: #333;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #3a8dff;
            margin-bottom: 20px;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }

        .success {
            background-color: #e1f5e1;
            color: #4CAF50;
        }

        .error {
            background-color: #ffebee;
            color: #F44336;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button {
            background-color: #3a8dff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #2370e1;
        }

        .error-message {
            color: #F44336;
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete an Evaluation</h1>

        <!-- Error message (displayed by JavaScript) -->
        <div id="error-message" class="error-message"></div>

        <!-- Form to enter the evaluation ID -->
        <form id="deleteForm" method="POST" action="">
            <label for="evaluationId">Evaluation ID to delete:</label>
            <input type="number" id="evaluationId" name="evaluationId" required placeholder="Enter the evaluation ID">
            <button type="submit">Delete</button>
        </form>
    </div>

    <script>
        // Select elements
        const form = document.getElementById('deleteForm');
        const evaluationIdInput = document.getElementById('evaluationId');
        const errorMessageDiv = document.getElementById('error-message');

        // Add event listener to the form
        form.addEventListener('submit', function (e) {
            const evaluationId = evaluationIdInput.value.trim();

            // Validation: check if the ID is a positive number
            if (!evaluationId || isNaN(evaluationId) || evaluationId <= 0) {
                e.preventDefault(); // Prevent form submission
                errorMessageDiv.textContent = "Please enter a valid ID (a positive number).";
            } else {
                // Clear the error message
                errorMessageDiv.textContent = "";
                if (!confirm('Are you sure you want to delete this evaluation?')) {
                    e.preventDefault(); // Prevent submission if user cancels confirmation
                }
            }
        });
    </script>
</body>
</html>
