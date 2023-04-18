<?php

namespace Marmi;

class RecetteCreator{
    function generateForm($titre, $action, $name, $image, $description, $ingredients, $tags,$instruction):void{
        $sep = DIRECTORY_SEPARATOR;?>

        <div id="MainForm">
            <h1 id="nvxrecette"><?=$titre?></h1>
            <form class = create action="<?= $action ?>" method="POST" enctype="multipart/form-data">
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
                    <input id="image" class="form-control" type="file" name="image" accept="image/png, image/gif, image/jpeg" value="..<?=$sep?>IMG<?=$sep?>Recette<?=$sep?><?=$image?>">
                </div>

                <div class="input-group">
                    <label class="label" for="description">
                        Description
                    </label>
                    <textarea id="description" class="form-control" name="description"><?=$description?></textarea>
                </div>

                <div id="FormIngredient" class="input-group">
                    <label class="label" for="ingredient">
                        Ingredients
                    </label>
                    <?php
                    if(sizeof($ingredients) == 0):?>
                        <input class="form-control inputIng" type="text" name="ingredients[]">
                    <?php endif;
                    foreach($ingredients as $ing):?>
                        <input class="form-control inputIng" type="text" name="ingredients[]" value="<?= $ing->getNom()?>">
                    <?php endforeach;

                    ?>


                    <div id="ControleBoutonIngr">
                        <div id="IngredientPlus">+</div>
                        <div id="IngredientMoins">-</div>
                    </div>

                </div>

                <div id="FormTag" class="input-group">
                    <label class="label" for="tag">
                        Tags
                    </label><?php
                    foreach($tags as $tag):
                        ?><input class="form-control inputIng" type="text" name="tag[]" value="<?= $tag->getNom()?>">
                    <?php endforeach;?>


                    <div id="ControleBoutonTag">
                        <div id="TagPlus">+</div>
                        <div id="TagMoins">-</div>
                    </div>
                </div>

                <div class="input-group">
                    <label id="labelInstruction" for="instruction">
                        Instruction
                    </label>
                    <textarea id="Instruction" class="form-control" name="instruction"><?=$instruction?></textarea>
                </div>


                <div id="boutons">
                    <button class="btn" type="submit">Submit</button>
                    <button class="btn" type="reset">Reset</button>
                </div>
            </form>
        </div>

        <?php
    }

    public function verify(string $name, array $image, string $description, array $ingredients, string $instruction) : string{
        $error = "";
        if(empty($name))
            $error = "name is empty";
        else if(empty($image))
            $error = "image is empty";
        else if(empty($description))
            $error = "description is empty";
        else if(empty($ingredients))
            $error = "Il doit y avoir au moins un ingrédient";
        else if(empty($instruction))
            $error = "Il doit y avoir au moins une instruction";
        return $error;
    }
}