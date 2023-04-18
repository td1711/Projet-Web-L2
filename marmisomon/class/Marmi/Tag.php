<?php

namespace Marmi;

class Tag{
    private String $nom;
    private int $id;


    function __construct(String $nom, int $id){
        $this->nom = $nom;
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getNom(): string
    {
        return $this->nom;
    }
}