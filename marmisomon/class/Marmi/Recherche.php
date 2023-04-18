<?php

namespace Marmi;

class Recherche{
    function get(String $recherche){
        $tab = [];
        return $tab;
    }

    function render(array $tab){
        foreach($tab as $elem){
            $elem->render();
        }
    }
}