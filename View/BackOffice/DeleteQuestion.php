<?php
include '../../controller/QuestionController.php';  // Assurez-vous que ce fichier existe et qu'il contient la méthode de suppression

// Vérifiez que l'ID est passé via GET et qu'il est valide
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $questionId = intval($_GET["id"]);  // Récupérer et convertir l'ID de la question

    try {
        // Instancier le contrôleur
        $questionController = new QuestionController();
        
        // Appeler la méthode de suppression de la question
        $questionController->deleteQuestion($questionId);

        // Redirection vers la liste des questions après suppression
        header('Location: listQuestion.php');  // Remplacez 'listQuestions.php' par la page où vous listez vos questions
        exit();  // Arrêter l'exécution après la redirection
    } catch (Exception $e) {
        // En cas d'erreur, affichez un message ou redirigez avec un message d'erreur
        echo "Erreur lors de la suppression de la question : " . $e->getMessage();
    }
} else {
    // Si l'ID est invalide ou non fourni, affichez une erreur ou redirigez
    echo "ID invalide ou non fourni.";
}
?>


