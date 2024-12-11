<?php
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php'; // Inclure le contrôleur des évaluations


$error = ""; // Variable pour afficher les erreurs si besoin

// Si le formulaire est soumis
if (
    isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["type"]) &&
    isset($_POST["date_limite"]) && isset($_POST["duree"]) && isset($_POST["idCours"]) &&
    isset($_POST["idEnseignant"])
) {
    // Vérification que tous les champs sont remplis
    if (
        !empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_POST["type"]) &&
        !empty($_POST["date_limite"]) && !empty($_POST["duree"]) && !empty($_POST["idCours"]) && !empty($_POST["idEnseignant"])
    ) {
        // Création d'une instance de la classe Evaluation avec les données du formulaire
        $evaluation = new Evaluation(
            null, // L'ID peut être auto-généré si nécessaire
            $_POST['titre'],  
            $_POST['description'],  
            $_POST['type'], 
            $_POST['date_limite'], // Date limite
            $_POST['duree'],  // Durée en minutes
            $_POST['idCours'], 
            $_POST['idEnseignant']  
        );

        // Instancier le contrôleur Evaluation
        $evaluationController = new EvaluationController();
        // Appel de la méthode pour ajouter l'évaluation
        $evaluationController->addEvaluation($evaluation);

        // Redirection après ajout réussi
        header('Location: ListEvaluation.php');
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
    <title>Add Evaluation</title>
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
            position: relative;
        }
        .form-container h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #0052cc;
        }
        .form-label {
            position: absolute;
            top: -8px;
            left: 10px;
            background: #ffffff;
            padding: 0 5px;
            font-size: 0.85rem;
            color: #0052cc;
            font-weight: 500;
        }
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 0.95rem;
            color: #333;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #0052cc;
            box-shadow: 0 0 5px rgba(0, 82, 204, 0.4);
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
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #555;
        }
        .form-footer a {
            color: #0052cc;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #ff0000;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Evaluation</h1>

        <!-- Form -->
        <form id="evaluationForm" action="addEvaluation.php" method="POST" novalidate>
            <div class="form-group">
                <label for="titre" class="form-label">Title</label>
                <input type="text" class="form-control" name="titre" id="titre" placeholder="Enter the title" required>
                <div id="error-titre" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter a description" required></textarea>
                <div id="error-description" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="" disabled selected>Select a type</option>
                    <option value="quiz">Quiz</option>
                    <option value="exam">Exam</option>
                    <option value="assignment">Assignment</option>
                </select>
                <div id="error-type" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="date_limite" class="form-label">Deadline</label>
                <input type="date" class="form-control" name="date_limite" id="date_limite" required>
                <div id="error-date_limite" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="duree" class="form-label">Duration (min)</label>
                <input type="number" class="form-control" name="duree" id="duree" placeholder="Enter the duration" min="1" required>
                <div id="error-duree" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="idCours" class="form-label">Course ID</label>
                <input type="number" class="form-control" name="idCours" id="idCours" placeholder="Enter the course ID" required>
                <div id="error-idCours" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="idEnseignant" class="form-label">Teacher ID</label>
                <input type="number" class="form-control" name="idEnseignant" id="idEnseignant" placeholder="Enter the teacher ID" required>
                <div id="error-idEnseignant" class="error-message"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add </button>
        </form>
    </div>

    <script>
        document.getElementById('evaluationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(e => e.textContent = '');

            // Validate title
            const titre = document.getElementById('titre');
            if (titre.value.trim() === '') {
                document.getElementById('error-titre').textContent = 'Title is required.';
                isValid = false;
            }

            // Validate description
            const description = document.getElementById('description');
            if (description.value.trim() === '') {
                document.getElementById('error-description').textContent = 'Description is required.';
                isValid = false;
            }

            // Validate type
            const type = document.getElementById('type');
            if (type.value === '') {
                document.getElementById('error-type').textContent = 'Please select a type.';
                isValid = false;
            }

            // Validate deadline
            const date_limite = document.getElementById('date_limite');
            if (date_limite.value === '') {
                document.getElementById('error-date_limite').textContent = 'Deadline is required.';
                isValid = false;
            }

            // Validate duration
            const duree = document.getElementById('duree');
            if (duree.value <= 0) {
                document.getElementById('error-duree').textContent = 'Duration must be greater than 0.';
                isValid = false;
            }

            // Validate course ID
            const idCours = document.getElementById('idCours');
            if (idCours.value.trim() === '') {
                document.getElementById('error-idCours').textContent = 'Course ID is required.';
                isValid = false;
            }

            // Validate teacher ID
            const idEnseignant = document.getElementById('idEnseignant');
            if (idEnseignant.value.trim() === '') {
                document.getElementById('error-idEnseignant').textContent = 'Teacher ID is required.';
                isValid = false;
            }

            // If valid, submit the form
            if (isValid) {
                this.submit();
            }
         
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
