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


    function getNom(){
        return $this->nom;
    }

    function getId(){
        return $this->id;
    }

    function getImage(){
        return $this->image;
    }

    function setImage(string $image){
        $this->image = $image;
    }

    function getSaison(){
        return $this -> saison;
    }

    function render(){

        ?>
        <div class="recetteTotale">
            <a class="lienRecette" href="">
                <div class="recette">
                    <img src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR."Ingredients".DIRECTORY_SEPARATOR.$this->image?>">
                    <div class="informations">
                        <h2 class="titre">
                            <?php echo $this->nom?>
                            <?php echo $this->saison?>
                        </h2>
                    </div>
                </div>
            </a>

            <?php if(isset($_SESSION["login"])):?>
             <div class="BoutonsRecette">
                    <a href="modifierIngredient.php?ingredient=<?= $this->id?>">
                        <button class="Modifier">
                            Modifier
                        </button>
                    </a>
                    <a href="delete.php?ingredient=<?= $this->id?>">
                        <button class="Supprimer">
                            Supprimer
                        </button>
                    </a>
                </div>
            <?php endif;?>


        </div>
        <?php
    }
}