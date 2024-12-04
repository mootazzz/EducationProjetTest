<?php
// Inclure le fichier de configuration ou le contrôleur
include '../../controller/QuestionController.php';

// Initialisation des variables
$errorMessage = "";
$successMessage = "";
$question = null;

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bd1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification si une ID a été passée pour la modification
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Si aucune ID n'est passée, récupérer toutes les questions
    $stmt = $pdo->query("SELECT idQuestion, contenu FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $questionId = intval($_GET['id']); // Validation de l'ID

    // Récupérer les détails de la question à modifier
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE idQuestion = :id");
    $stmt->execute([':id' => $questionId]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$question) {
        die("Erreur : Aucune question trouvée avec l'ID $questionId.");
    }
}

// Traitement du formulaire soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contenu = $_POST['contenu'] ?? '';
    $type = $_POST['type'] ?? '';
    $options = $_POST['options'] ?? '';
    $bonneReponse = $_POST['bonneReponse'] ?? '';
    $points = $_POST['points'] ?? '';

    // Validation des champs
    if (empty($contenu) || empty($type) || empty($bonneReponse) || empty($points)) {
        $errorMessage = "Tous les champs sont obligatoires.";
    } else {
        try {
            $sql = "UPDATE question 
                    SET contenu = :contenu, 
                        type = :type, 
                        options = :options, 
                        bonneReponse = :bonneReponse, 
                        points = :points 
                    WHERE idQuestion = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':contenu' => $contenu,
                ':type' => $type,
                ':options' => $options,
                ':bonneReponse' => $bonneReponse,
                ':points' => $points,
                ':id' => $questionId,
            ]);
            $successMessage = "La question a été modifiée avec succès.";
        } catch (PDOException $e) {
            $errorMessage = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
        header('Location: listQuestion.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Question</title>
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
        <h1>Update Question</h1>

        <!-- Messages d'erreur ou de succès -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!isset($question)): ?>
            <!-- Sélection d'une question -->
            <form method="get">
                <div class="mb-3">
                    <label for="questionId" class="form-label"> </label>
                    <select class="form-select" name="id" id="questionId">
                        <option value="">Select</option>
                        <?php foreach ($questions as $q): ?>
                            <option value="<?php echo $q['idQuestion']; ?>"><?php echo $q['contenu']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Confirmer</button>
            </form>
        <?php else: ?>
            <!-- Formulaire de modification -->
            <form method="post">
                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu</label>
                    <input type="text" class="form-control" name="contenu" id="contenu" value="<?php echo htmlspecialchars($question['contenu']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" name="type" id="type" required>
                        <option value="choix_multiples" <?php echo $question['type'] == 'choix_multiples' ? 'selected' : ''; ?>>Choix Multiples</option>
                        <option value="vrai_faux" <?php echo $question['type'] == 'vrai_faux' ? 'selected' : ''; ?>>Vrai/Faux</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="options" class="form-label">Options</label>
                    <textarea class="form-control" name="options" id="options" rows="2"><?php echo htmlspecialchars($question['options']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="bonneReponse" class="form-label">Bonne Réponse</label>
                    <input type="text" class="form-control" name="bonneReponse" id="bonneReponse" value="<?php echo htmlspecialchars($question['bonneReponse']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="points" class="form-label">Points</label>
                    <input type="number" class="form-control" name="points" id="points" value="<?php echo htmlspecialchars($question['points']); ?>" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Modifier</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
