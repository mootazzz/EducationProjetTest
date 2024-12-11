<?php
include '../../controller/EvaluationController.php';

// Vérifiez que l'ID est passé via GET
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $evaluationId = intval($_GET["id"]);

    try {
        // Instanciez le contrôleur
        $evaluationController = new EvaluationController();
        
        // Appelez la méthode de suppression
        $evaluationController->deleteEvaluation($evaluationId);

        // Redirection vers la liste des évaluations
        header('Location: ListEvaluation.php');
        exit(); // Assurez-vous d'arrêter l'exécution après la redirection
    } catch (Exception $e) {
        // En cas d'erreur, affichez un message ou redirigez avec un message d'erreur
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }
} else {
    // Si l'ID est invalide, affichez une erreur ou redirigez
    echo "ID invalide ou non fourni.";
}
?>