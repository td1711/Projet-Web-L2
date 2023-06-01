<?php

namespace Marmi;

class Recette{
    private String $titre;
    private array $Ingredients;
    private String $description;
    private String $instruction;
    private String $photo;
    private array $tags;
    private int $id;

    function __construct(String $titre, String $photo, String $description, String $instruction, int $id){
        $this->titre = $titre;
        $this->Ingredients = array();
        $this->tags = array();
        $this->photo = $photo;
        $this->description = $description;
        $this->instruction = $instruction;
        $this->id = $id;
    }

    public function getId(): int{
        return $this -> id;
    }

    /**
     * @return String
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @return String
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getIngredients(): array
    {
        return $this->Ingredients;
    }

    /**
     * @return String
     */
    public function getPhoto(): string
    {
        return $this->photo;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getInstruction(): String{
        return $this->instruction;
    }

    function addIngredients(array $nouveaux){
        foreach($nouveaux as $nv){
            if(!in_array($nv,$this->Ingredients) && $nv->getNom() != ""){
                $this->Ingredients[] = $nv;
            }
        }
    }

    function addTags(array $nouveaux){
        foreach($nouveaux as $nv){
            if(!in_array($nv, $this -> tags)){
                $this -> tags[] = $nv;
            }
        }
    }

    function render(){

        ?>
        <div class="recetteTotale">
            <a class="lienRecette" href="recette.php?recette=<?= $this->id?>">
                <div class="recette">
                    <img src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR."Recette".DIRECTORY_SEPARATOR.$this->photo?>">
                    <div class="informations">
                        <h2 class="titre">
                            <?php echo $this->titre?>
                        </h2>

                        <div class="Description-Ing">
                            <div class="ingredients">
                                <b>Ingrédients :</b>
                                <?php
                                foreach($this->Ingredients as $ing):
                                    ?>
                                    <div class="ing">
                                        <?= $ing->getNom()?>
                                    </div>
                                <?php endforeach;?>
                            </div>

                            <div class="description">
                                <b>Description :</b>
                                <?php echo $this->description?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php // Affichage des boutons de modification et de suppression si l'utilisateur est connecté
            if(isset($_SESSION["login"])):?>
            <div class="BoutonsRecette">
                <a href="modifier.php?recette=<?= $this->id?>">
                    <button class="Modifier">
                        Modifier
                    </button>
                </a>
                <a href="delete.php?recette=<?= $this->id?>">
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