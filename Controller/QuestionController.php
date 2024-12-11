<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../Model/Question.php');

class QuestionController
{
    // Liste toutes les questions
    public function listQuestions()
    {
        $sql = "SELECT * FROM questions";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            $questions = [];
            foreach ($liste->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $questions[] = new Question(
                    $row['idQuestion'],
                    $row['contenu'],
                    $row['type'],
                    json_decode($row['options'], true), // Décoder les options JSON
                    $row['bonne_reponse'],
                    $row['points'],
                    $row['idEvaluation'] // Ajout de idEvaluation
                );
            }
            return $questions;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération des questions : ' . $e->getMessage());
        }
    }

    // Supprime une question
    public function deleteQuestion($id)
    {
        $sql = "DELETE FROM questions WHERE idQuestion = :id";
        $db = config::getConnexion();
        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            $req->execute();
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    // Ajoute une nouvelle question
    public function addQuestion(Question $question)
    {
        $sql = "
            INSERT INTO questions (contenu, type, options, bonne_reponse, points, idEvaluation)
            VALUES (:contenu, :type, :options, :bonne_reponse, :points, :idEvaluation)
        ";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'contenu' => $question->getContenu(),
                'type' => $question->getType(),
                'options' => json_encode($question->getOptions()), // Encodage en JSON
                'bonne_reponse' => $question->getBonneReponse(),
                'points' => $question->getPoints(),
                'idEvaluation' => $question->getIdEvaluation(), // Ajout de idEvaluation
            ]);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de l\'ajout : ' . $e->getMessage());
        }
    }

    // Modifie une question existante
    public function updateQuestion(Question $question, $id)
    {
        $sql = "
            UPDATE questions SET 
                contenu = :contenu,
                type = :type,
                options = :options,
                bonne_reponse = :bonne_reponse,
                points = :points,
                idEvaluation = :idEvaluation
            WHERE idQuestion = :id
        ";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'contenu' => $question->getContenu(),
                'type' => $question->getType(),
                'options' => json_encode($question->getOptions()), // Encodage en JSON
                'bonne_reponse' => $question->getBonneReponse(),
                'points' => $question->getPoints(),
                'idEvaluation' => $question->getIdEvaluation(), // Ajout de idEvaluation
                'id' => $id,
            ]);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // Affiche les détails d'une question spécifique
    public function showQuestion($id)
    {
        $sql = "SELECT * FROM questions WHERE idQuestion = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new Question(
                    $data['idQuestion'],
                    $data['contenu'],
                    $data['type'],
                    json_decode($data['options'], true), // Décoder les options JSON
                    $data['bonne_reponse'],
                    $data['points'],
                    $data['idEvaluation'] // Ajout de idEvaluation
                );
            }
            return null; // Si aucune question n'est trouvée
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération : ' . $e->getMessage());
        }
    }

    public function getAllQuestions()
{
    $sql = "SELECT * FROM questions"; // Adapter selon votre table
    $stmt = config::getConnexion()->query($sql);
    return $stmt->fetchAll(); // Retourne toutes les questions
}

}


?>
