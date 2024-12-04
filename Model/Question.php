<?php

class Question
{
    private ?int $idQuestion = null;
    private ?string $contenu = null; // Texte de la question
    private ?string $type = null; // Type de question (choix multiples, vrai/faux, réponse courte)
    private ?array $options = []; // Liste des options (si choix multiples)
    private ?string $bonneReponse = null; // La bonne réponse
    private ?int $points = null; // Points attribués

    // Constructeur
    public function __construct(
        $id = null,
        $contenu = null,
        $type = null,
        $options = [],
        $bonneReponse = null,
        $points = null
    ) {
        $this->idQuestion = $id;
        $this->contenu = $contenu;
        $this->type = $type;
        $this->options = $options;
        $this->bonneReponse = $bonneReponse;
        $this->points = $points;
    }

    // Getters
    public function getIdQuestion() { return $this->idQuestion; }
    public function getContenu() { return $this->contenu; }
    public function getType() { return $this->type; }
    public function getOptions() { return $this->options; }
    public function getBonneReponse() { return $this->bonneReponse; }
    public function getPoints() { return $this->points; }

    // Setters
    public function setContenu($contenu) { $this->contenu = $contenu; return $this; }
    public function setType($type) { $this->type = $type; return $this; }
    public function setOptions($options) { $this->options = $options; return $this; }
    public function setBonneReponse($bonneReponse) { $this->bonneReponse = $bonneReponse; return $this; }
    public function setPoints($points) { $this->points = $points; return $this; }


}

?>
