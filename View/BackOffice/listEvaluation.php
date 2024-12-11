<?php 
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php'; // Inclure le contrôleur des évaluations
$evaluationController = new EvaluationController();
$list = $evaluationController->listEvaluations();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Evaluation List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0052cc, #66b2ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            margin-top: 50px;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            color: #0052cc;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 4px solid black;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 4px solid black;
            font-size: 1rem;
        }

        table th {
            background-color: #0052cc;
            color: #0052cc;
            font-weight: bold;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        table tr:hover td {
            background-color: #66b2ff;
            transition: background-color 0.3s;
        }

    table td a,
    table td input[type="submit"] {
    font-size: 0.9rem;
    width: 120px; /* Largeur fixe pour uniformiser les boutons */
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: inline-block;
    border: 2px solid white; /* Contour noir par défaut */
    color: white; /* Texte blanc */
    }

    /* Bouton d'avertissement (warning) */
    .btn-warning {
    background-color: #66b2ff;
    color: white; /* Texte blanc */
    border: 2px solid black; /* Contour noir par défaut */
    }

    .btn-warning:hover {
    background-color: #0052cc;
    border: 2px solid white; /* Contour blanc au survol */
    color: white; /* Texte blanc au survol */
    }

    .btn-warning:active {
    background-color: #0052cc; /* Couleur au clic (lorsque le bouton est enfoncé) */
    border: 2px solid white; /* Contour blanc maintenu après le clic */
    color: white; /* Texte blanc maintenu */
    }

    /* Bouton de danger (danger) */
    .btn-danger {
    background-color: #66b2ff;
    color: white; /* Texte blanc */
    border: 2px solid black; /* Contour noir par défaut */
    }

    .btn-danger:hover {
    background-color: #0052cc;
    border: 2px solid white; /* Contour blanc au survol */
    color: white; /* Texte blanc au survol */
    }

    .btn-danger:active {
    background-color: #0052cc; /* Couleur au clic (lorsque le bouton est enfoncé) */
    border: 2px solid white; /* Contour blanc maintenu après le clic */
    color: white; /* Texte blanc maintenu */
    }

    /* Bouton d'information (info) */
    .btn-info {
    background-color: #66b2ff;
    color: white; /* Texte blanc */
    border: 2px solid black; /* Contour noir par défaut */
    }

    .btn-info:hover {
    background-color: #0052cc;
    border: 2px solid white; /* Contour blanc au survol */
    color: white; /* Texte blanc au survol */
    }

    .btn-info:active {
    background-color: #3399cc; /* Couleur au clic (lorsque le bouton est enfoncé) */
    border: 2px solid white; /* Contour blanc maintenu après le clic */
    color: white; /* Texte blanc maintenu */
    } 

    </style>
</head>

<body>
    <div class="container">
        <h1>List Of Evaluations</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Evaluation ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Deadline</th>
                    <th>Duration (min)</th>
                    <th>Course ID</th>
                    <th>Teacher ID</th>
                    <th colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $evaluation): ?>
                    <tr>
                        <td><?= $evaluation->getIdEvaluation(); ?></td>
                        <td><?= $evaluation->getTitre(); ?></td>
                        <td><?= $evaluation->getDescription(); ?></td>
                        <td><?= $evaluation->getType(); ?></td>
                        <td><?= $evaluation->getDateLimite(); ?></td>
                        <td><?= $evaluation->getDuree(); ?></td>
                        <td><?= $evaluation->getIdCours(); ?></td>
                        <td><?= $evaluation->getIdEnseignant(); ?></td>
                        <td>
                        <form method="POST" action="updateEvaluation.php">
                        <!-- Remplacer l'input par un bouton -->
                            <button type="submit" name="update" class="btn btn-warning btn-sm">Update</button>
                              <input type="hidden" value="<?= $evaluation->getIdEvaluation(); ?>" name="id">
                        </form>
                        </td>

                        <td>
                            <a href="#" onclick="confirmDelete(<?= $evaluation->getIdEvaluation(); ?>)" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td>
                            <a href="viewQuestions.php?id=<?= $evaluation->getIdEvaluation(); ?>" class="btn btn-info btn-sm">View Questions</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to confirm deletion
        function confirmDelete(id) {
            const userConfirmed = confirm("Are you sure you want to delete this evaluation?");
            if (userConfirmed) {
                // Redirect to the delete URL if confirmed
                window.location.href = "DeleteEvaluation.php?id=" + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
