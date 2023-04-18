<?php

namespace Marmi;

class IngredientCreator
{
    function generateForm($name, $image, $description):void{?>
        <div id="MainForm">
            <h1 id="nvxIngredient">Nouveau Ingrédient</h1>
            <form class = create action="createIngredient.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label class="label" for="name">
                        Titre
                    </label>
                    <input id="name" class="form-control" type="text" name="name" value="<?=$name?>">
                </div>

                <div class="input-group">
                    <label class="label" for="image">
                        Image
                    </label>
                    <input id="image" class="form-control" type="file" name="image" accept="image/png, image/gif, image/jpeg" value="<?=$image?>">
                </div>

                <div class="input-group">
                    <label class="label" for="description">
                        Description
                    </label>
                    <textarea id="description" class="form-control" name="description">
                        <?=$description?>
                    </textarea>
                </div>
            </form>
        </div>


        <?php
    }

    public function verify(string $name, array $image, string $description) : string{
        $error = "";
        if(empty($name))
            $error = "name is empty";
        else if(empty($image))
            $error = "image is empty";
        else if(empty($description))
            $error = "description is empty";
        return $error;
    }

}