<?php

namespace Marmi;

class RecetteCreator{

    function generateIngredientForm($ingredients, $saisons){?>
        <div id="FormIngredient">
            <div id="Labels">
                <label class="label" for="ingredient">
                    Ingredients
                </label>
                <label class="label" hidden="">
                    Saison
                </label>
            </div>

            <div id="IngredientsTest">
                <div id="IngredientsGroup">

                    <?php
                    // Si on vient d'arriver sur le formulaire (dans create.php)
                    if(empty($ingredients)):?>
                        <div class="DivCreationIngredient">
                            <input list="selection" class="form-control inputIng" type="text" name="ingredients[]">
                            <?php $MarmiDB = new \Marmi\MarmiDB();
                            $listeIngredients = $MarmiDB->returnArrayIngredients();
                            // Affichage des différents ingrédients existants dans l'input?>
                            <datalist id="selection">
                                <?php foreach($listeIngredients as $ing):?>
                                    <option value="<?=$ing->getNom()?>">
                                <?php endforeach;?>
                            </datalist>
                        </div>

                    <?php endif;
                    $indice = 0;

                    for($i=0; $i<count($ingredients); $i++):
                        // Si on modifie une recette, on va créer un input par ingrédient (sans input pour saison et image car ils existent déjà)

                        // Sinon, lors de la soumission du formulaire, si il y a des erreurs, le formulaire est regénéré avec les informations fournies
                        // Pour chaque ingrédient entré, on va donc vérifier si il est dans la BDD, sinon ajouter les inputs saisons et images
                        ?>
                        <div class="DivCreationIngredient">
                            <input class="form-control inputIng" type="text" name="ingredients[]" value="<?= $ingredients[$i]->getNom()?>" list="browsers">

                            <?php $MarmiDB = new MarmiDB();
                            // On vérifie si l'ingrédient existe ( dans le cas où le formulaire est regénéré après une erreur )
                            if($ingredients[$i]->getNom() != "" && !$MarmiDB->testIngredient($ingredients[$i]->getNom())):?>

                                <input class="form-control saison" type="text" name="saison[]" value="<?= $saisons[$indice]?>">
                                <?php $indice++;?>
                                <div class="ImageForm">

                                    <img class="ImageIng" src="../IMG/add_image.svg">
                                    <input class="image" type="file" name="imageIng[]" accept="image/png, image/gif, image/jpeg">
                                </div>
                            <?php endif?>
                        </div>
                    <?php endfor;
                    ?>
                </div>
            </div>

        </div>
        <?php
    }

    function generateTagsForm($tags){?>
        <div class="input-group" id="DivTags">
            <div id="FormTag">
                <label class="label" for="tag">
                    Tags
                </label>

                <input list="selectionTag" class="form-control inputTag" name ="tags[]" type="text" >

                <?php
                // // Affichage des différents tags existants dans l'input
                $MarmiDB = new \Marmi\MarmiDB();
                $listeTags = $MarmiDB->getAllTags("");?>
                <datalist id="selectionTag">
                    <?php foreach($listeTags as $tag):?>
                    <option value="<?=$tag->getNom()?>">
                        <?php endforeach;?>
                </datalist>

            </div>
            <div id="DisabledTags">
                <?php
                if(!empty($tags)) {
                    foreach($tags as $tag):?>
                    <div class="tagGroup">
                        <input class="form-control tag" type="text" name="tags[]" value="<?=$tag->getNom()?>">
                        <div class="Croix">X</div>
                    </div>
                    <?php endforeach;
                }
                ?>
            </div>
        </div>
        <?php
    }
    function generateForm($titre, $action, $name, $image, $description, $ingredients, $tags,$instruction, $saisons):void{
        $sep = DIRECTORY_SEPARATOR;
        ?>
        <h1 id="nvxrecette"><?=$titre?></h1>
        <div id="MainForm">
            <form class = create action="<?= $action ?>" method="POST" enctype="multipart/form-data">

                <div id="titleImage">
                    <div class="input-group">
                        <label class="label" for="name">
                            Titre
                        </label>

                        <input id="name" class="form-control" type="text" name="name" value="<?=$name?>">
                    </div>
                    <div id="ImageForm" class="input-group">
                        <label class="label" for="image">
                            Image
                        </label>

                        <img id="ImageImg" <?php if($image !=""){echo "src='..".$sep."IMG".$sep."Recette".$sep.$image."'";}?>>
                            <input id="image" class="form-control btn" type="file" name="image" accept="image/png, image/gif, image/jpeg" onchange="change()">
                    </div>

                </div>
                <?php $this->generateTagsForm($tags);?>

                <div id="bloc">
                    <div id="blocGauche">
                        <div class="input-group" id="FormDesc">
                            <label class="label" for="description">
                                Description
                            </label>
                            <?php $txt = "Ajoutez une courte description"?>
                            <textarea id="description" class="form-control" name="description" placeholder="<?=$txt?>"><?=$description?></textarea>
                        </div>

                        <div class="input-group">
                            <label id="labelInstruction" for="instruction">
                                Instructions
                            </label>
                            <?php $txt = "Décrivez la recette en détails ici"?>
                            <textarea id="Instruction" class="form-control" name="instruction" placeholder="<?=$txt?>"><?=$instruction?></textarea>
                        </div>
                    </div>

                    <?php $this->generateIngredientForm($ingredients, $saisons);?>

                </div>
                <div id="boutons">
                    <button id="submit" type="submit">Submit</button>
                </div>
        </div>



        </form>
        </div>

        <?php
    }

    // Vérifie les différentes informations saisies par l'utilisateur
    public function verify(string $name, array $image, string $description, array $ingredients, string $instruction) : string{
        $error = "";

        if(empty($name))
            $error = "Le nom est vide";
        else if($image['error'] != 0)
            $error = "L'image est vide";
        else if(empty($description))
            $error = "La description est vide";
        else if(empty($ingredients) || $ingredients[0]->getNom() == "") {
            $error = "Il doit y avoir au moins un ingrédient";
        }
        else if(empty($instruction))
            $error = "Il doit y avoir au moins une instruction";
        return $error;
    }
}