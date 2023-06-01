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

    public function render():void{?>
        <div class="tagRender">
            <h2 class="titreTag">
                <?php echo $this->nom?>
            </h2>
            <?php if(isset($_SESSION["login"])):?>
                <a href="delete.php?tag=<?= $this->id?>">
                    <img src="../IMG/delete.svg">
                </a>
            <?php endif; ?>
        </div>
        <?php


    }
}