<?php

// Class Question (modifiée pour garantir que getOptions() retourne toujours un tableau)
class Question
{
    private ?int $idQuestion = null;
    private ?string $contenu = null;
    private ?string $type = null;
    private ?array $options = []; // Liste des options (si choix multiples)
    private ?string $bonneReponse = null;
    private ?int $points = null;
    private ?int $idEvaluation;

    public function __construct($id, $contenu, $type, $options, $bonneReponse, $points, $idEvaluation)
    {
        $this->idQuestion = $id;
        $this->contenu = $contenu;
        $this->type = $type;
        $this->options = is_array($options) ? $options : []; // Vérifier si $options est un tableau
        $this->bonneReponse = $bonneReponse;
        $this->points = $points;
        $this->idEvaluation = $idEvaluation;
    }

    public function getIdQuestion() { return $this->idQuestion; }
    public function getContenu() { return $this->contenu; }
    public function getType() { return $this->type; }
    public function getOptions() { return $this->options; }
    public function getBonneReponse() { return $this->bonneReponse; }
    public function getPoints() { return $this->points; }
    public function getIdEvaluation() { return $this->idEvaluation; }

    public function setContenu($contenu) { $this->contenu = $contenu; return $this; }
    public function setType($type) { $this->type = $type; return $this; }
    public function setOptions($options) { $this->options = $options; return $this; }
    public function setBonneReponse($bonneReponse) { $this->bonneReponse = $bonneReponse; return $this; }
    public function setPoints($points) { $this->points = $points; return $this; }
    public function setIdEvaluation($idEvaluation) { $this->idEvaluation = $idEvaluation; }
}


?>
