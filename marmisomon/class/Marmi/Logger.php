<?php

namespace Marmi;

class Logger{ // Classe Logger du TP2
    public array $tab = array("granted" => true, "nick" => "", "error" => null);
    public function generateLoginForm(string  $action) : void{?>

        <form method="post" action=<?=$action?> id="form2">
            <div id="bordureconnexion">
                <form id="connexion" method="post" action=<?=$action?> id="form2">
                    <div id="text-login">
                        Veuillez vous connecter
                    </div>
                    <div id="form-group">
                        <input type="text" name="username" placeholder="username" id="identifiant">
                        <input type="password" name="password" placeholder="password" id="motdepasse">
                    </div>
                    <button type="submit" id="Soumettre">Login</button>
                </form>
            </div>
        </form>

        <?php
    }

    public function log(string $username, string $password) : array{
        if($username != "Admin" || $password != "Admin") {
            $this->tab["granted"] = false;
            $this->tab["nick"] = null;
            $this->tab["error"] = "Authentification failed";
        }
        else
            $this->tab["nick"] = $username;
        if(empty($username))
            $this->tab["error"] = "username is empty";
        else if(empty($password))
            $this->tab["error"] = "password is empty";
        return $this->tab;
    }
}