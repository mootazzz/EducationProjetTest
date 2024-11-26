<?php
include '../../controller/EvaluationController.php';

$error = ""; // Variable pour afficher les erreurs si besoin
$evaluationController = new EvaluationController();

// Récupérer l'ID de l'évaluation à partir de l'URL
if (isset($_GET['id'])) {
    $evaluation_id = $_GET['id'];
    
    // Récupérer l'évaluation à partir de la base de données
    $evaluation = $evaluationController->getEvaluationById($evaluation_id);
    
    if (!$evaluation) {
        // Si l'évaluation n'existe pas
        echo "Évaluation non trouvée!";
        exit;
    }
} else {
    echo "ID d'évaluation manquant!";
    exit;
}

// Si le formulaire est soumis pour la mise à jour
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
        // Mise à jour de l'évaluation avec les nouvelles données
        $evaluation = new Evaluation(
            $evaluation_id,  // ID existant pour la mise à jour
            $_POST['titre'],  
            $_POST['description'],  
            $_POST['type'], 
            $_POST['date_limite'], // Date limite
            $_POST['duree'],  // Durée en minutes
            $_POST['idCours'], 
            $_POST['idEnseignant']  
        );

        // Appel de la méthode pour mettre à jour l'évaluation
        $evaluationController->updateEvaluation($evaluation);

        // Redirection après mise à jour réussie
        header('Location: listEvaluation.php');
        exit;
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour l'évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Mettre à jour l'évaluation</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="updateEvaluation.php?id=<?php echo $evaluation->getId(); ?>">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" id="titre" value="<?php echo $evaluation->getTitre(); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required><?php echo $evaluation->getDescription(); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="quiz" <?php if ($evaluation->getType() == 'quiz') echo 'selected'; ?>>Quiz</option>
                    <option value="exam" <?php if ($evaluation->getType() == 'exam') echo 'selected'; ?>>Exam</option>
                    <option value="assignment" <?php if ($evaluation->getType() == 'assignment') echo 'selected'; ?>>Assignment</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_limite" class="form-label">Date limite</label>
                <input type="date" class="form-control" name="date_limite" id="date_limite" value="<?php echo $evaluation->getDateLimite(); ?>" required>
            </div>

            <div class="mb-3">
                <label for="duree" class="form-label">Durée (minutes)</label>
                <input type="number" class="form-control" name="duree" id="duree" value="<?php echo $evaluation->getDuree(); ?>" min="1" required>
            </div>

            <div class="mb-3">
                <label for="idCours" class="form-label">ID Cours</label>
                <input type="number" class="form-control" name="idCours" id="idCours" value="<?php echo $evaluation->getIdCours(); ?>" required>
            </div>

            <div class="mb-3">
                <label for="idEnseignant" class="form-label">ID Enseignant</label>
                <input type="number" class="form-control" name="idEnseignant" id="idEnseignant" value="<?php echo $evaluation->getIdEnseignant(); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour l'évaluation</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
