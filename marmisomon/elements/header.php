<header>

    <div id="menu">
        <a href="index.php" id="titlelogo">
            <img id="spat" src="../IMG/spatule.png">
            <div id="main-title">
                Marmisomon
            </div>
        </a>

        <form method="post" action="recherche.php" id="form">
            <div id="barrerecherche"><?php
                // Si l'utilisateur vient de faire une recherche
                // on affiche la recherche dans la barre de recherche de la nouvelle page
                $search = "";
                if(isset($_POST["search"]))
                    $search = htmlspecialchars($_POST["search"]);
                ?>
                <input class="searchbar" type="text" name="search" placeholder="Je cherche une recette" value="<?=$search?>">
                <input hidden="hidden" name="sort" value="Tout">
                <button type="submit" id="valider" ><img id="valideimage" src="../IMG/Loupe.png"></button>
            </div>
        </form>
    </div>

    <div id="liens">
        <?php
        // Si l'utilisateur est connecté
        if(isset($_SESSION["login"])):
        ?>
            <div id="connecte">
                <a href="create.php">
                    <div id="create" class="lien">
                        Créer une recette
                    </div>
                </a>

                <a href="logout.php">
                    <div id="loginAdmin" class="lien">
                        Logout <?php echo $_SESSION["login"];?>
                    </div>
                </a>
            </div>


        <?php else:?>
            <a href="login.php">
                <div id="loginAdmin" class="lien">
                    Connexion Admin
                </div>
            </a>
        <?php endif?>
    </div>
</header>