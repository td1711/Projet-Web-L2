<?php

namespace Marmi;

use PDO;

class MarmiDB
{
    public PDO $PDO;

    public function __construct()
    {
        // Informations sur la BDD et le serveur qui la contient
        $db_name = "marmisomon" ; // marmisomon
        $db_host = "127.0.0.1" ; // 127.0.0.1
        $db_port = "3306" ; // Port par défaut de MySQL

// Informations d'authentification de votre script PHP
        $db_user = "root" ; // root
        $db_pwd = "" ;  //

// Connexion à la BDD
        try{
            // Agrégation des informations de connexion dans une chaine DSN (Data Source Name)
            $dsn = 'mysql:dbname=' . $db_name . ';host='. $db_host. ';port=' . $db_port;

            // Connexion et récupération de l'objet connecté
            $this -> PDO = new PDO($dsn, $db_user, $db_pwd);
        }

// Récupération d'une éventuelle erreur
        catch (\Exception $ex){
            // Arrêt de l'exécution du script PHP
            die("Erreur : " . $ex->getMessage()) ;
        }

// Si pas d'erreur : poursuite de l'exécution
    }

    function doRequete(string $requete):array{
        $sql = $requete;
        $statement = $this -> PDO -> prepare($sql);
        $statement -> execute() or die(var_dump($statement -> errorInfo()));
        $results = $statement -> fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    function supprimerRecette(int $id):void{
        /** Supprime la recette de la base de donnée.
         *
         *  $id = id de la recette
         */
        $query = "DELETE FROM recette WHERE id = ". $id ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));

        $query = "DELETE FROM contientTag WHERE id_recette = " . $id ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));

        $query = "DELETE FROM contientIngredient WHERE id_recette = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function modifierRecette(int $id, string $nomAttribut, string $nouveau){
        /** Permet de modifier une recette.
         *
         * $id : id de la recette
         * $nomAttribut : nom de l'attribut modifié, doit être = "titre" "description" "image"
         * $nouveau : nouvelle valeur de l'attribut modifié
         */
        $query = "UPDATE recette SET " . $nomAttribut ." = \"" . $nouveau . "\" where id = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement -> execute() or die(var_dump($statement) -> errorInfo());
    }

    function supprimerTag(int $id):void{
        /** Supprime le tag de la base de donnée.
         *
         *  $id = id du tag
         */
        $query = "DELETE FROM tag WHERE id = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));
        $query = "DELETE FROM contientTag WHERE id_tag = " . $id ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function modifierTag(int $id, string $nomAttribut, string $nouveau){
        /** Permet de modifier un tag.
         *
         * $id : id du tag
         * $nomAttribut : nom de l'attribut modifié, doit être = "nom"
         * $nouveau : nouvelle valeur de l'attribut modifié
         */
        $query = "UPDATE tag SET " . $nomAttribut ." = \"" . $nouveau . "\" where id = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement -> execute() or die(var_dump($statement) -> errorInfo());
    }


    function supprimerIngredient(int $id):void{
        /** Supprime l'ingrédient de la base de donnée.
         *
         *  $id = id de l'ingrédient
         */
        $query = "DELETE FROM ingredient WHERE id = " . $id ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));

        $query = "DELETE FROM contientIngredient WHERE id_ingredient = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function modifierIngredient(int $id, string $nomAttribut, string $nouveau){
        /** Permet de modifier un tag.
         *
         * $id : id de l'ingredient
         * $nomAttribut : nom de l'attribut modifié, doit être = "nom" "image" "saison"
         * $nouveau : nouvelle valeur de l'attribut modifié
         */
        $query = "UPDATE ingredient SET " . $nomAttribut ." = \"" . $nouveau . "\" where id = " . $id;
        $statement = $this -> PDO -> prepare($query);
        $statement -> execute() or die(var_dump($statement) -> errorInfo());
    }

    function testIngredients(Recette $recette): array{
        /**  Cherche les ingredients de la recette n'étant pas dans la base de donnée.
         *
         * $recette : La recette testée
         *
         * return : renvoie un array des noms des Ingrédients manquants.
         */
        $manquant = array();

        foreach($recette -> getIngredients() as $ingredient):
            if(! $this->testIngredient($ingredient -> nom)){
                $manquant[] = $ingredient -> getNom();
            }
        endforeach;

            return $manquant;
    }



    function addRecette(Recette $recette){
        $PDO = $this->PDO;
        $query = "INSERT INTO recette(titre, description, image, instruction) VALUES (:titre, :desc, :img, :ins)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':titre', $recette -> getTitre());
        $statement->bindValue(':desc', $recette -> getDescription());
        $statement->bindValue(':img', $recette -> getPhoto());
        $statement->bindValue('ins', $recette -> getInstruction());
        $statement->execute() or die(var_dump($statement -> errorInfo()));


        $statement = $this -> PDO -> prepare("SELECT id FROM recette WHERE titre = \"" . $recette -> getTitre() . "\"");
        $statement -> execute() or die(var_dump($statement -> errorInfo()));
        $id = $statement -> fetchAll();
        echo $id[0][0];

        $query = "INSERT INTO contientTag(id_recette, id_tag) VALUES (:recette, :tag)";
        $statement = $PDO -> prepare($query);
        foreach ($recette -> getTags() as $tag):
            $statement->bindValue(':recette', $id[0]);
            $statement->bindValue(':tag', $tag -> getId());
            $statement->execute() or die(var_dump($statement -> errorInfo()));
        endforeach;

        $query = "INSERT INTO contientIngredient(id_recette, id_ingredient) VALUES (:recette, :ingredient)";
        $statement = $PDO -> prepare($query);
        foreach ($recette -> getIngredients() as $ingredient):
            $statement->bindValue(':recette', $id[0][0]);
            $statement->bindValue(":ingredient", $ingredient -> getId());
            $statement->execute() or die(var_dump());
        endforeach;
    }

    function addTag(Tag $tag):void{
        $PDO = $this->PDO;
        $query = "INSERT INTO tag(nom) VALUES (:nom)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':nom', $tag -> getNom());
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function addIngredient(Ingredient $ingredient):void{
        $PDO = $this->PDO;
        $query = "INSERT INTO ingredient(nom, image, saison) VALUES (:nom, :img, :saison)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':nom', $ingredient -> getNom());
        $statement->bindValue(':img', $ingredient -> getImage());
        $statement->bindValue(":saison", $ingredient -> getSaison());
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function testIngredient(String $name):bool{
        $ingrediants = $this -> doRequete("SELECT * FROM ingredient WHERE nom = \"" . $name . "\"");
        return count($ingrediants) >= 1;
    }

    function testTag(String $name):bool{
        $tags = $this -> doRequete("SELECT * FROM tag WHERE nom = \"" . $name . "\"");
        return count($tags) >= 1;
    }

    function getAllRecettes(string $recherche):array{
        /** Renvoie toutes les recettes contenant la recherche.
         *
         * $string: string contenant une suite de caractere obligatoire dans le nom des recettes cherchées
         *
         * return: renvoie un array contenant les recettes
         */

        $listeSelection = array();
        $requete = "SELECT * FROM recette WHERE titre LIKE \"%" . $recherche . "%\""; //Base de donnée TP n°2
        $results = $this->doRequete($requete);

        foreach($results as $result):
            $recette = new Recette($result -> titre, $result -> image, $result -> description, $result -> instruction, $result -> id);
            //cours_bd_SQL_4
            $tags = $this->doRequete("SELECT nom, tag.id from tag cross join contientTag cross join recette where id_recette = recette.id and id_tag = tag.id and id_recette = ". $recette -> getId()) ;


            $ingredients = $this->doRequete("SELECT ingredient.id, nom, ingredient.image as image, saison from ingredient cross join contientIngredient cross join recette where id_recette = recette.id and id_ingredient = ingredient.id and id_recette = ". $recette -> getId());
            $listeIngredients = array();

            foreach($ingredients as $ingredient):
                $listeIngredients[] = new Ingredient($ingredient -> nom, $ingredient -> image, "", $ingredient -> id);
            endforeach;

            $listeTags = array();

            foreach ($tags as $tag):
                $listeTags[] = new Tag($tag -> nom, $tag -> id);
            endforeach;

            $recette -> addTags($listeTags);
            $recette -> addIngredients($listeIngredients);
            $listeSelection[] = $recette;

        endforeach;
        return $listeSelection;
    }

    function returnRecette(int $id):Recette{
        $requete = "SELECT * FROM recette WHERE id=".$id;
        $results = $this->doRequete($requete);
        $result = $results[0];
        $recette = new Recette($result -> titre, $result -> image, $result -> description, $result -> instruction, $result -> id);
        $ingredients = $this->doRequete("SELECT ingredient.id, nom, ingredient.image as image, saison from ingredient cross join contientIngredient cross join recette where id_recette = recette.id and id_ingredient = ingredient.id and id_recette = ". $recette -> getId());
        $listeIngredients = array();

        foreach($ingredients as $ingredient):
            $listeIngredients[] = new Ingredient($ingredient -> nom, $ingredient -> image, "", $ingredient -> id);
        endforeach;
        $recette -> addIngredients($listeIngredients);
        return $recette;
    }
}
