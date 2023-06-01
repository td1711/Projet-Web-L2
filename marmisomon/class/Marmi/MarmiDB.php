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

    function recupererImageIngredient(int $id):array{
        $requete = "SELECT image FROM ingredient WHERE id = ". $id;
        return $this->doRequete($requete);
    }

    function returnIngredient(int $id):array{
        $requete = "SELECT * FROM ingredient WHERE id = ". $id;
        return $this->doRequete($requete);
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

    function supprimerIngredientRecette(int $idRecette, string $nomIngredient){
        /** Permet de supprimer un ingredient d'une recette.
         *
         * $idRecette : id de la recette dont l'ingrédient va être supprimer
         * $nomIngredient: nom de l'ingrédient supprimé
         */
        $idIngredient = $this->getIdIngredient($nomIngredient);
        $query = "DELETE FROM contientIngredient WHERE id_recette = " . $idRecette . " AND id_ingredient = ". $idIngredient ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));

    }

    function supprimerTagRecette(int $idRecette, string $nomTag){
        /** Permet de supprimer un tag d'une recette.
         *
         * $idRecette : id de la recette dont l'ingrédient va être supprimer
         * $nomTag : nom du tag supprimé
         */
        $idTag = $this-> getIdTag($nomTag);
        $query = "DELETE FROM contientTag WHERE id_recette = " . $idRecette . " AND id_tag = ". $idTag ;
        $statement = $this -> PDO -> prepare($query);
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function supprimerToutIngredient(Recette $recette){
        /** Permet de supprimer tous les ingrédients d'une recette
         *
         * $recette : objet de type recette dont tous les ingrédients vont être supprimé
         */
        $requete = "Select nom FROM ingredient join contientIngredient join recette WHERE ingredient.id = id_ingredient and contientIngredient.id_recette =" . $recette -> getId();

        $ingredients = $this->doRequete($requete);
        foreach($ingredients as $ingredient){
            $this->supprimerIngredientRecette($recette->getId(), $ingredient -> nom);
        }
    }

    function supprimerToutTag(Recette $recette){
        /** Permet de supprimer tous les tags d'une recette.
         *
         * $recette : objet de type recette dont tous les ingrédients vont être supprimé
         */
        $requete = "Select nom FROM tag join contientTag join recette WHERE tag.id = id_tag and contientTag.id_recette =" . $recette -> getId();
        $tags = $this->doRequete($requete);
        foreach ($tags as $tag){
            $this->supprimerTagRecette($recette->getId(), $tag->nom);
        }
    }

    function modifierIngredientRecette(Recette $recette, $nouveauxIngredients){
        /** Permet de modifier les ingrédients d'une recette, (supprime tout les anciens ingrédients et ajoute les nouveaux)
         *
         * $recette : objet de type recette dont les ingrédients vont être modifiés
         * $nouveauxIngredients : liste d'objet ingrédients qui seront ajouté à recette
         */
        $this->supprimerToutIngredient($recette);
        $PDO = $this->PDO;
        $query = "INSERT INTO contientIngredient(id_recette, id_ingredient) VALUES (:rec, :ing)";
        $statement = $PDO -> prepare($query);
        foreach ($nouveauxIngredients as $ing){
            $statement->bindValue(':rec', $recette -> getId());
            $statement->bindValue(":ing", $this->getIdIngredient($ing -> getNom()));
            $statement->execute() or die(var_dump());
        }
    }

    function modifierTagRecette(Recette $recette, $nouveauxTags){
        /** Permet de modifier les tags d'une recette, (supprime tout les anciens tags et ajoute les nouveaux)
         *
         * $recette : objet de type recette dont les tags vont être modifiés
         * $nouveauxTags : liste d'objet tags qui seront ajouté à recette
         */
        $this->supprimerToutTag($recette);
        $PDO = $this->PDO;
        $query = "INSERT INTO contientTag(id_recette, id_tag) VALUES (:rec, :tag)";
        $statement = $PDO -> prepare($query);
        foreach ($nouveauxTags as $tag){
            $statement->bindValue(':rec', $recette -> getId());
            $statement->bindValue(':tag', $this->getIdTag($tag -> getNom()));
            $statement->execute() or die(var_dump());
        }
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
        /** Permet de modifier un ingrédient.
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
         * return : renvoie un array des Ingrédients manquants.
         */
        $manquant = array();

        foreach($recette -> getIngredients() as $ingredient):
            if(! $this->testIngredient($ingredient->getNom())){
                $manquant[] = $ingredient;
            }
        endforeach;

            return $manquant;
    }

    function testTags(Recette $recette): array{
        /**  Cherche les tags de la recette n'étant pas dans la base de donnée.
         *
         * $recette : La recette testée
         *
         * return : renvoi un array des Tags manquants.
         */
        $manquant = array();

        foreach($recette -> getTags() as $tag):
            if(! $this->testTag($tag->getNom())){
                $manquant[] = $tag;
            }
        endforeach;

        return $manquant;
    }

    function getIdIngredient(string $NomIngredient) : int{
        /** Renvoi l'id d'un ingrédient par rapport à son nom.
         *
         * $NomIngredient : Nom de l'ingrédient testé.
         *
         * return : renvoi un int de l'id de l'ingrédient.
         */
        $requete = "SELECT id FROM ingredient WHERE nom='".$NomIngredient."'";
        $results = $this->doRequete($requete);
        $result = $results[0];
        return $result->id;
    }

    function getIdTag(string $NomTag) : int{
        /** Renvoi l'id d'un tag par rapport à son nom.
         *
         * $NomTag : Nom de l'ingrédient testé.
         *
         * return : renvoi un int de l'id du tag.
         */
        $requete = "SELECT id FROM tag WHERE nom='".$NomTag."'";
        $results = $this->doRequete($requete);
        $result = $results[0];
        return $result->id;
    }

    function addRecette(Recette $recette){
        /** Ajoute une recette dans la base de donnée
         *
         * $recette : Objet de type recette qui sera mise dans la base de donnée.
         */
        $PDO = $this->PDO;
        $query = "INSERT INTO recette(titre, description, image, instruction) VALUES (:titre, :desc, :img, :ins)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':titre', $recette -> getTitre());
        $statement->bindValue(':desc', $recette -> getDescription());
        $statement->bindValue(':img', $recette -> getPhoto());
        $statement->bindValue('ins', $recette -> getInstruction());
        $statement->execute() or die(var_dump($statement -> errorInfo()));
        $statement = $this -> PDO -> prepare("SELECT * from recette WHERE id=(SELECT max(id) FROM recette)");
        $statement -> execute() or die(var_dump($statement -> errorInfo()));
        $id = $statement -> fetchAll();
        $query = "INSERT INTO contientTag(id_recette, id_tag) VALUES (:recette, :tag)";
        $statement = $PDO -> prepare($query);
        foreach ($recette -> getTags() as $tag):
            $statement->bindValue(':recette', $id[0][3]);
            $statement->bindValue(':tag', $this -> getIdTag($tag->getNom()));
            $statement->execute() or die(var_dump($statement -> errorInfo()));
        endforeach;
        $query = "INSERT INTO contientIngredient(id_recette, id_ingredient) VALUES (:recette, :ingredient)";
        $statement = $PDO -> prepare($query);
        foreach ($recette -> getIngredients() as $ingredient):
            $statement->bindValue(':recette', $id[0][3]);
            $statement->bindValue(":ingredient", $this->getIdIngredient($ingredient->getNom()));
            $statement->execute() or die(var_dump());
        endforeach;
    }

    function addTag(Tag $tag):void{
        /** Ajoute un tag à la base de donnée.
         *
         * $tag: Objet de type tag qui sera mise dans la base de donnée.
         */
        $PDO = $this->PDO;
        $query = "INSERT INTO tag(nom) VALUES (:nom)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':nom', $tag -> getNom());
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function addIngredient(Ingredient $ingredient):void{
        /** Ajoute un ingrédient à la base de donnée.
         *
         * $ingredient: Objet de type ingrédient qui sera mis dans la base de donnée.
         */
        $PDO = $this->PDO;
        $query = "INSERT INTO ingredient(nom, image, saison) VALUES (:nom, :img, :saison)";
        $statement = $PDO -> prepare($query);
        $statement->bindValue(':nom', $ingredient -> getNom());
        $statement->bindValue(':img', $ingredient -> getImage());
        $statement->bindValue(":saison", $ingredient -> getSaison());
        $statement->execute() or die(var_dump($statement -> errorInfo()));
    }

    function testIngredient(String $name):bool{
        /** Test de si un ingrédient est présent dans la base de donnée
         *
         * $name : nom de l'ingrédient testé
         * $return : boolean, true si l'élément est présent | false sinon
         */
        $ingrediants = $this -> doRequete("SELECT * FROM ingredient WHERE nom = \"" . $name . "\"");
        return count($ingrediants) >= 1;
    }

    function testTag(String $name):bool{
        /** Test de si un tag est présent dans la base de donnée
         *
         * $name : nom du tag testé
         * $return : boolean, true si l'élément est présent | false sinon
         */
        $tags = $this -> doRequete("SELECT * FROM tag WHERE nom = \"" . $name . "\"");
        return count($tags) >= 1;
    }

    function getAllRecettes(string $requete):array{
        /** Renvoie toutes les recettes contenant la recherche.
         *
         * $requete: string contenant une suite de caractere obligatoire dans le nom des recettes cherchées
         *
         * return: renvoie un array contenant les recettes
         */
        $listeSelection = array();
        $results = $this->doRequete($requete);
        foreach($results as $result):
            $recette = $this->returnRecette($result->id);
            //cours_bd_SQL_4

            $listeSelection[] = $recette;

        endforeach;

        return $listeSelection;
    }

    function getAllIngredient(string $recherche):array{
        /** Renvoie tous les ingrédients contenant la recherche.
         *
         * $recherche: string contenant une suite de caractere obligatoire dans le nom des ingredients cherchés
         *
         * return: renvoie un array contenant les ingrédients
         */
        $sql = "SELECT * FROM ingredient WHERE nom LIKE \"%" . $recherche . "%\"";
        $results = $this->doRequete($sql);
        $listeSelection = array();
        foreach ($results as $result){
            $saison ="";
            if($result->saison == null)
                $saison = "";
            $ingredient = new Ingredient($result -> nom, $result -> image, $saison, $result -> id);
            $listeSelection[] = $ingredient;
        }
        return $listeSelection;
    }

    function getAllTags(string $recherche){
        /** Renvoie tous les tags contenant la recherche.
         *
         * $recherche: string contenant une suite de caractere obligatoire dans le nom des tags cherchés
         *
         * return: renvoie un array contenant les tags
         */
        $sql = "SELECT * FROM tag WHERE nom LIKE \"%" . $recherche . "%\" ORDER BY nom asc";
        $results = $this->doRequete($sql);
        $listeSelection = array();
        foreach ($results as $result){
            $tag = new Tag($result -> nom, $result -> id);
            $listeSelection[] = $tag;
        }
        return $listeSelection;
    }

    function recherche(string $recherche){
        /** Renvoie les recettes correspondant a la recherche
         *
         * $recherche : string contenant une suite de caractere obligatoire dans le nom des recettes cherchées
         * $return : array d'objet recette qui correspondent a la recherche
         */
        $requete = "SELECT * FROM recette WHERE titre LIKE \"%" . $recherche . "%\""; //Base de donnée TP n°2
        return $this->getAllRecettes($requete);
    }


    function getRechercheAvance(string $recherche, $ingredients, $tags):array{
        /** Renvoie les recettes correspondant à la recherche par nom, à la liste d'ingrédient et à la liste des tags
         *
         * $recherche : string contenant une suite de caractere obligatoire dans le nom des recettes cherchées
         * $ingredients : liste des noms d'ingrédients devant être dans la recette
         * $tags : liste des noms de tags devant être dans la recette
         *
         * $return : array d'objet recette qui correspondent a la recherche
         */

        $idIngredients = array();
        $idTags = array();
        if(!empty($ingredients) and !empty($ingredients[0]) ) {
            foreach ($ingredients as $ingredient) {
                if(!$this->testIngredient($ingredient)){
                    return array();
                }
                $idIngredients[] = $this->getIdIngredient($ingredient);
            }
        }
        if(!empty($tags)){
            foreach ($tags as $tag){
                if(!$this->testTag($tag)){
                    return array();
                }
                $idTags[] = $this->getIdTag($tag);
            }
        }

        $requete = "SELECT DISTINCT(recette.id) FROM recette";

        if(!empty($idTags)){
            $requete .= " JOIN contienttag ON recette.id = contienttag.id_recette JOIN tag ON contienttag.id_tag = tag.id";;
        }

        if(!empty($idIngredients)) {
            $requete .= " JOIN contientingredient ON recette.id = contientingredient.id_recette JOIN ingredient ON contientingredient.id_ingredient = ingredient.id";
        }
        $requete .= " WHERE titre LIKE \"%" . $recherche . "%\"";

        if(!empty($idIngredients)){
            $requete .= "AND ingredient.id IN (";
            $i = 0;
            foreach ($idIngredients as $idIngredient) {
                $requete .= "'$idIngredient'";
                if ($i < count($idIngredients) - 1)
                    $requete .= ",";
                $i++;
            }
            $requete .= ")";
        }

        if(!empty($idTags)){
            $requete .= " AND tag.id IN (";
            $i = 0;
            foreach ($idTags as $idTag) {
                $requete .= "'$idTag'";
                if ($i < count($idTags) - 1)
                    $requete .= ",";
                $i++;
            }
            $requete .= ")";
        }

        if(!empty($idTags) || !empty($idIngredients)){
            $requete .= " GROUP BY recette.id HAVING";
            if(!empty($idIngredients)){
                $TailleIngs = count($idIngredients);
                $requete .= " COUNT(DISTINCT ingredient.id) = $TailleIngs";
            }
            if(!empty($idTags)){
                if(!empty($idIngredients))
                    $requete .= " AND";
                $TailleTags = count($idTags);
                $requete .= " COUNT(DISTINCT tag.id) = $TailleTags";
            }
        }

        return $this->getAllRecettes($requete);

    }

    function returnRecette(int $id): ?Recette{
        $requete = "SELECT * FROM recette WHERE id=".$id;
        $results = $this->doRequete($requete);
        $result = $results[0];
        if($result == null)
            return null;
        $recette = new Recette($result -> titre, $result -> image, $result -> description, $result -> instruction, $result -> id);

        $ingredients = $this->doRequete("SELECT ingredient.id, nom, ingredient.image as image, saison from ingredient cross join contientIngredient cross join recette where id_recette = recette.id and id_ingredient = ingredient.id and id_recette = ". $recette -> getId());
        $tags = $this->doRequete("SELECT nom, tag.id from tag cross join contientTag cross join recette where id_recette = recette.id and id_tag = tag.id and id_recette = ". $recette -> getId()) ;

        //$ingredients = $this->doRequete("SELECT ingredient.id, nom, ingredient.image as image, saison from ingredient JOIN contientingredient ON ingredient.id = contientingredient.id_ingredient JOIN recette ON contientingredient.id_recette = recette.id where id_recette = ". $recette -> getId());
        $listeIngredients = array();

        //$tags = $this->doRequete("SELECT  nom, tag.id from tag JOIN contienttag ON tag.id = contienttag.id_tag JOIN recette ON contienttag.id_recette = recette.id WHERE id_recette = ". $recette -> getId()) ;
        $listetags = array();

        foreach($ingredients as $ingredient):
            $saison =$ingredient->saison;
            if($saison == null)
                $saison = "";

            $listeIngredients[] = new Ingredient($ingredient -> nom, $ingredient -> image, $saison, $ingredient -> id);
        endforeach;
        $recette -> addIngredients($listeIngredients);

        foreach($tags as $tag):
            $listetags[] = new Tag($tag -> nom, $tag -> id);
        endforeach;
        $recette -> addTags($listetags);
        return $recette;
    }

    function returnArrayIngredients(){
        $requete = "SELECT * FROM ingredient";
        $results = $this->doRequete($requete);
        $liste = array();
        foreach($results as $result){
            $saison ="";
            if($result->saison == null)
                $saison = "";
            $liste[] = new Ingredient($result->nom, $result->image, $saison, $result->id);
        }
        return $liste;
    }

    function returnNewId():int{
        $statement = $this -> PDO -> prepare("SELECT * from recette WHERE id=(SELECT max(id) FROM recette)");
        $statement -> execute() or die(var_dump($statement -> errorInfo()));
        $id = $statement -> fetchAll();

        return $id[0][3]+1;
    }
}
