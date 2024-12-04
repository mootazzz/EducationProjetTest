<?php
// Inclure le fichier de configuration
include '../../controller/EvaluationController.php';

// Initialisation des variables
$errorMessage = "";
$successMessage = "";
$evaluation = null;

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bd1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification si une ID a été passée pour la modification
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Si aucune ID n'est passée, récupérer toutes les évaluations
    $stmt = $pdo->query("SELECT idEvaluation, titre FROM evaluation");
    $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $evaluationId = intval($_GET['id']); // Validation de l'ID

    // Récupérer les détails de l'évaluation à modifier
    $stmt = $pdo->prepare("SELECT * FROM evaluation WHERE idEvaluation = :id");
    $stmt->execute([':id' => $evaluationId]);
    $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evaluation) {
        die("Erreur : Aucune évaluation trouvée avec l'ID $evaluationId.");
    }
}

// Traitement du formulaire soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['type'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $courseId = $_POST['courseId'] ?? '';
    $teacherId = $_POST['teacherId'] ?? '';

    // Traitement du formulaire soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['type'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $courseId = $_POST['courseId'] ?? '';
    $teacherId = $_POST['teacherId'] ?? '';

    // Validation des champs
    if (empty($titre) || empty($description) || empty($type) || empty($deadline) || empty($duration) || empty($courseId) || empty($teacherId)) {
        $errorMessage = "Tous les champs sont obligatoires.";
    } elseif (!is_numeric($duration) || $duration <= 0) {
        $errorMessage = "La durée doit être un nombre positif.";
    } elseif (strtotime($deadline) < time()) {
        $errorMessage = "La date limite ne peut pas être dans le passé.";
    } else {
        // Mettre à jour l'évaluation
        try {
            $sql = "UPDATE evaluation 
                    SET titre = :titre, 
                        description = :description, 
                        type = :type, 
                        date_limite = :deadline, 
                        duree = :duration, 
                        idCours = :courseId, 
                        idEnseignant = :teacherId 
                    WHERE idEvaluation = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':type' => $type,
                ':deadline' => $deadline,
                ':duration' => $duration,
                ':courseId' => $courseId,
                ':teacherId' => $teacherId,
                ':id' => $evaluationId,
            ]);
            $successMessage = "L'évaluation a été modifiée avec succès.";
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
        header('Location: listEvaluation.php');
        exit;
    }
}
 else {
        // Mettre à jour l'évaluation
        try {
            $sql = "UPDATE evaluation 
                    SET titre = :titre, 
                        description = :description, 
                        type = :type, 
                        date_limite = :deadline, 
                        duree = :duration, 
                        idCours = :courseId, 
                        idEnseignant = :teacherId 
                    WHERE idEvaluation = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':type' => $type,
                ':deadline' => $deadline,
                ':duration' => $duration,
                ':courseId' => $courseId,
                ':teacherId' => $teacherId,
                ':id' => $evaluationId,
            ]);
            $successMessage = "L'évaluation a été modifiée avec succès.";
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
        header('Location: listEvaluation.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0052cc, #66b2ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
            color: #0052cc;
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
        .error-message {
            color: #ff0000;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Update an Evaluation</h1>

        <!-- Messages d'erreur ou de succès -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!isset($evaluation)): ?>
            <!-- Sélection d'une évaluation -->
            <form method="get">
                <div class="mb-3">
                    <label for="evaluationId" class="form-label"> </label>
                    <select class="form-select" name="id" id="evaluationId">
                        <option value="">Select Evaluation</option>
                        <?php foreach ($evaluations as $e): ?>
                            <option value="<?php echo $e['idEvaluation']; ?>"><?php echo $e['titre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Confirm</button>
            </form>
        <?php else: ?>
            <!-- Formulaire de modification -->
            <form method="post">
                <div class="mb-3">
                    <label for="titre" class="form-label">Title</label>
                    <input type="text" class="form-control" name="titre" id="titre" value="<?php echo htmlspecialchars($evaluation['titre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($evaluation['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" name="type" id="type" required>
                        <option value="quiz" <?php echo $evaluation['type'] == 'quiz' ? 'selected' : ''; ?>>Quiz</option>
                        <option value="exam" <?php echo $evaluation['type'] == 'exam' ? 'selected' : ''; ?>>Exam</option>
                        <option value="assignment" <?php echo $evaluation['type'] == 'assignment' ? 'selected' : ''; ?>>Assignment</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" class="form-control" name="deadline" id="deadline" value="<?php echo htmlspecialchars($evaluation['date_limite']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (min)</label>
                    <input type="number" class="form-control" name="duration" id="duration" value="<?php echo htmlspecialchars($evaluation['duree']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="courseId" class="form-label">IdCours</label>
                    <input type="number" class="form-control" name="courseId" id="courseId" value="<?php echo htmlspecialchars($evaluation['idCours']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="teacherId" class="form-label">IdTeacher</label>
                    <input type="number" class="form-control" name="teacherId" id="teacherId" value="<?php echo htmlspecialchars($evaluation['idEnseignant']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
