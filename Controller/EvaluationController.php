<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../Model/Evaluation.php');

class EvaluationController
{
    // Liste toutes les évaluations
    public function listEvaluations()
    {
        $sql = "SELECT * FROM evaluation";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            $evaluations = [];
            if ($liste->rowCount() > 0) {
                foreach ($liste->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $evaluation = new Evaluation(
                        $row['idEvaluation'],
                        $row['titre'],
                        $row['description'],
                        $row['type'],
                        $row['date_limite'],
                        $row['duree'],
                        $row['idCours'],
                        $row['idEnseignant']
                    );
                    $evaluations[] = $evaluation;
                }
            }
            return $evaluations;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    // Supprime une évaluation
    public function deleteEvaluation($id)
    {
        $sql = "DELETE FROM evaluation WHERE idEvaluation = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Ajoute une nouvelle évaluation
    public function addEvaluation($evaluation)
    {
          $sql = "INSERT INTO evaluation (titre, description, type, date_limite, duree, idCours, idEnseignant) 
               VALUES (:titre, :description, :type, :date_limite, :duree, :idCours, :idEnseignant)";
    
          $db = config::getConnexion();
    
        // Convertir la date en objet DateTime si nécessaire
        $dateLimite = $evaluation->getDateLimite();
        if ($dateLimite instanceof DateTime) {
            $dateLimiteFormatted = $dateLimite->format('Y-m-d');
        } else {
            // Si ce n'est pas un objet DateTime, assurez-vous qu'il est formaté correctement
            $dateLimiteFormatted = date('Y-m-d', strtotime($dateLimite));
        }
    
        try {
        $query = $db->prepare($sql);
        $query->bindParam(':titre', $evaluation->getTitre());
        $query->bindParam(':description', $evaluation->getDescription());
        $query->bindParam(':type', $evaluation->getType());
        $query->bindParam(':date_limite', $dateLimiteFormatted);  // Utilisation de la variable correctement formatée
        $query->bindParam(':duree', $evaluation->getDuree());
        $query->bindParam(':idCours', $evaluation->getIdCours());
        $query->bindParam(':idEnseignant', $evaluation->getIdEnseignant());
        $query->execute();
        } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        }   
    }


    // Modifie une évaluation existante
    public function updateEvaluation($evaluation, $id)
    {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE evaluation SET 
                    titre = :titre,
                    description = :description,
                    type = :type,
                    date_limite = :date_limite,
                    duree = :duree,
                    idCours = :idCours,
                    idEnseignant = :idEnseignant
                WHERE idEvaluation = :id'
            );
            $query->bindParam(':id', $id);
            $query->bindParam(':titre', $evaluation->getTitre());
            $query->bindParam(':description', $evaluation->getDescription());
            $query->bindParam(':type', $evaluation->getType());
            $query->bindParam(':date_limite', $evaluation->getDateLimite()->format('Y-m-d'));
            $query->bindParam(':duree', $evaluation->getDuree());
            $query->bindParam(':idCours', $evaluation->getIdCours());
            $query->bindParam(':idEnseignant', $evaluation->getIdEnseignant());
            $query->execute();
            echo $query->rowCount() . " record(s) UPDATED successfully<br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getEvaluationById($id) {
        $query = "SELECT * FROM evaluation WHERE idEvaluation = :id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If an evaluation is found, create an Evaluation object
        if ($evaluation) {
            return new Evaluation(
                $evaluation['id'],
                $evaluation['titre'],
                $evaluation['description'],
                $evaluation['type'],
                $evaluation['date_limite'],
                $evaluation['duree'],
                $evaluation['idCours'],
                $evaluation['idEnseignant']
            );
        }
    
        return null;
    }
    

    // Affiche les détails d'une évaluation spécifique
    public function showEvaluation($id)
    {
        $sql = "SELECT * FROM evaluation WHERE idEvaluation = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listEvaluationsWithQuestions()
    {
        try {
            $evaluationsWithQuestions = Evaluation::getEvaluationsWithQuestions();
            include(__DIR__ . '/../View/evaluations_with_questions.php'); // Inclut la vue
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }



    public function listQuestionsByEvaluation($idEvaluation) {
        // Logique pour récupérer les questions de l'évaluation par ID
        $query = "SELECT * FROM questions WHERE idEvaluation = :idEvaluation";
        $db = config::getConnexion(); // Utilisation de la méthode pour obtenir la connexion
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idEvaluation', $idEvaluation, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les résultats sous forme de tableau associatif
    }
    
    
    
    
    
}
?>
