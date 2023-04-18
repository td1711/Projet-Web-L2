<?php

namespace Marmi;

class Ingredient{
    private String $nom;
    private String $image;
    private String $saison;

    private int $id;

    function __construct(String $nom, String $image, String $saison, int $id){
        $this->nom = $nom;
        $this->image = $image;
        $this->saison = $saison;
        $this->id = $id;
    }

    function printNom(){
        echo $this->nom;
    }

    function getNom(){
        return $this->nom;
    }

    function getId(){
        return $this->id;
    }

    function getImage(){
        return $this->image;
    }

    function getSaison(){
        return $this -> saison;
    }
}