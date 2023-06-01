<?php

namespace Marmi;

class IngredientCreator
{
    function generateForm($name, $image, $saison, $action):void{?>
        <div id="IngredientForm">

            <form class="create" action=<?=$action?> method="POST" enctype="multipart/form-data">
                <h1 id="nvxIngredient" style="text-align:center">Modifier un ingrédient</h1>
                <div class="input-group">
                    <label class="label" for="name">
                        Titre
                    </label>
                    <input id="name" class="form-control" type="text" name="name" value="<?=$name?>">
                </div>

                <div class="input-group">
                    <label class="label" for="saison">
                        Saison
                    </label>
                    <input id="saison" class="form-control" name="saison" value="<?=$saison?>">
                </div>

                <div id="ImageGroupIng" class="input-group">
                    <div class="ImageForm">
                        <img id="ImageImg" src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR
                        ."Ingredients".DIRECTORY_SEPARATOR.$image?>">
                        <input id="image" type="file" name="imageIng" accept="image/png, image/gif, image/jpeg">
                    </div>
                </div>

                <div id="boutons">
                    <button id="submit" type="submit">Submit</button>
                </div>
            </form>
        </div>


        <?php
    }

    public function verify(string $name, array $image, int $id) : string{
        $error = "";
        $MarmiDB = new MarmiDB();
        if($MarmiDB->testIngredient($_POST["name"]) && $id != $MarmiDB->getIdIngredient($name))
            $error = "Ce nom d'ingrédient existe déjà";
        else if(empty($name))
            $error = "name is empty";
        else if($image["error"] != 0)
            $error = "image is empty";

        return $error;
    }

}