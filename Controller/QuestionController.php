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
            if ($liste->rowCount() > 0) {
                foreach ($liste->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $question = new Question(
                        $row['idQuestion'],
                        $row['contenu'],
                        $row['type'],
                        json_decode($row['options'], true), // Décoder le JSON en tableau PHP
                        $row['bonne_reponse'],
                        $row['points']
                    );
                    $questions[] = $question;
                }
            }
            return $questions;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Supprime une question
    public function deleteQuestion($id)
    {
        $sql = "DELETE FROM questions WHERE idQuestion = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Ajoute une nouvelle question
    public function addQuestion($question)
    {
        $sql = "INSERT INTO questions (contenu, type, options, bonne_reponse, points) 
                VALUES (:contenu, :type, :options, :bonneReponse, :points)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':contenu', $question->getContenu());
            $query->bindParam(':type', $question->getType());
            $query->bindParam(':options', json_encode($question->getOptions())); // Encoder en JSON
            $query->bindParam(':bonneReponse', $question->getBonneReponse());
            $query->bindParam(':points', $question->getPoints());
            $query->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Modifie une question existante
    public function updateQuestion($question, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE questions SET 
                    contenu = :contenu,
                    type = :type,
                    options = :options,
                    bonne_reponse = :bonneReponse,
                    points = :points
                WHERE idQuestion = :id'
            );
            $query->bindParam(':id', $id);
            $query->bindParam(':contenu', $question->getContenu());
            $query->bindParam(':type', $question->getType());
            $query->bindParam(':options', json_encode($question->getOptions()));
            $query->bindParam(':bonneReponse', $question->getBonneReponse());
            $query->bindParam(':points', $question->getPoints());
            $query->execute();
            echo $query->rowCount() . " record(s) UPDATED successfully<br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Affiche les détails d'une question spécifique
    public function showQuestion($id)
    {
        $sql = "SELECT * FROM questions WHERE idQuestion = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new Question(
                    $data['idQuestion'],
                    $data['contenu'],
                    $data['type'],
                    json_decode($data['options'], true), // Décoder les options JSON
                    $data['bonne_reponse'],
                    $data['points']
                );
            }
            return null;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
