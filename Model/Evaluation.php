<?php

class Evaluation
{
    private ?int $idEvaluation = null;
    private ?string $titre = null;
    private ?string $description = null;
    private ?string $type = null; 
    private ?string $date_limite = null;
    private ?int $duree = null; 
    /*private ?array $questions = [];*/
    private ?int $idCours = null; 
    private ?int $idEnseignant = null; 

    public function __construct($id = null, $titre = null, $desc = null, $type = null, $date = null, $duree = null, $cours = null, $enseignant = null)
    {
        $this->idEvaluation = $id;
        $this->titre = $titre;
        $this->description = $desc;
        $this->type = $type;
        $this->date_limite = $date;
        $this->duree = $duree;
        $this->idCours = $cours;
        $this->idEnseignant = $enseignant;
    }

    

    
    public function getIdEvaluation() { return $this->idEvaluation; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getType() { return $this->type; }
    public function getDateLimite() { return $this->date_limite; }
    public function getDuree() { return $this->duree; }
    /*public function getQuestions() { return $this->questions; }*/
    public function getIdCours() { return $this->idCours; }
    public function getIdEnseignant() { return $this->idEnseignant; }

    
    public function setTitre($titre) { $this->titre = $titre; return $this; }
    public function setDescription($description) { $this->description = $description; return $this; }
    public function setType($type) { $this->type = $type; return $this; }
    public function setDateLimite($date) { $this->date_limite = $date; return $this; }
    public function setDuree($duree) { $this->duree = $duree; return $this; }
    /*public function setQuestions($questions) { $this->questions = $questions; return $this; }*/
    public function setIdCours($idCours) { $this->idCours = $idCours; return $this; }
    public function setIdEnseignant($idEnseignant) { $this->idEnseignant = $idEnseignant; return $this; }





        // Ajoutez une méthode pour récupérer les évaluations avec leurs questions
        public static function getEvaluationsWithQuestions()
        {
            $sql = "
                SELECT 
                    e.idEvaluation, 
                    e.titre, 
                    e.description, 
                    q.idQuestion, 
                    q.contenu, 
                    q.type, 
                    q.options, 
                    q.bonne_reponse, 
                    q.points
                FROM 
                    evaluation e
                LEFT JOIN 
                    questions q 
                ON 
                    e.idEvaluation = q.idEvaluation
            ";
    
            $db = config::getConnexion();
            try {
                $stmt = $db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
            } catch (PDOException $e) {
                die('Error: ' . $e->getMessage());
            }
        }
}
?>
