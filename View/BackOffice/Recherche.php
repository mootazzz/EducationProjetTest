<?php
include '../../controller/EvaluationController.php';

// Récupération des critères de recherche depuis l'URL ou formulaire
$titre = isset($_GET['titre']) ? $_GET['titre'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$dateLimite = isset($_GET['date_limite']) ? $_GET['date_limite'] : '';
$niveau = isset($_GET['niveau']) ? $_GET['niveau'] : '';

// Création du contrôleur pour accéder aux évaluations
$evaluationController = new EvaluationController();

// Construction de la requête SQL dynamique
$sql = "SELECT * FROM evaluation WHERE 1";

// Filtrage par titre
if ($titre) {
    $sql .= " AND titre LIKE :titre";
}

// Filtrage par type
if ($type) {
    $sql .= " AND type = :type";
}

// Filtrage par date limite
if ($dateLimite) {
    $sql .= " AND date_limite <= :date_limite";
}

// Filtrage par niveau (si applicable, à définir dans la table d'évaluation)
if ($niveau) {
    $sql .= " AND niveau = :niveau";
}

try {
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);

    // Binds des paramètres de recherche
    if ($titre) {
        $stmt->bindValue(':titre', '%' . $titre . '%');
    }
    if ($type) {
        $stmt->bindValue(':type', $type);
    }
    

    $stmt->execute();
    $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche des évaluations</title>
    <style>
        /* Style général du body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        /* Style du container principal */
        .container {
            width: 80%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Style du titre */
        h1 {
            text-align: center;
            color: #333;
            font-size: 32px;
            margin-bottom: 30px;
        }

        /* Style du formulaire */
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="date"] {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            max-width: 400px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Style du tableau des résultats */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Message d'absence de résultats */
        p {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recherche des évaluations</h1>

        <form method="get" action="recherche.php">
            <input type="text" name="titre" placeholder="Titre de l'évaluation" value="<?= htmlspecialchars($titre) ?>">
            <input type="text" name="type" placeholder="Type d'évaluation" value="<?= htmlspecialchars($type) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <h2>Résultats de la recherche</h2>

        <?php if (count($evaluations) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evaluations as $evaluation): ?>
                        <tr>
                            <td><?= htmlspecialchars($evaluation['titre']) ?></td>
                            <td><?= htmlspecialchars($evaluation['type']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune évaluation ne correspond à vos critères.</p>
        <?php endif; ?>
    </div>
</body>
</html>
