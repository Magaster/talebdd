<?php

class ControleurProduits {

    public function __construct() {
        // si on séparait les modèles, le constructeur donnerait son chemin
        // require_once Chemins::MODELES.'gestion_categories.class.php';    
    }

    public function afficher($libelleCategorie) {
        VariablesGlobales::$libelleCategorie = $libelleCategorie;
        VariablesGlobales::$lesProduits = GestionBoutique::getLesProduitsByCategorie($libelleCategorie);
        require Chemins::VUES . 'v_produits.inc.php';        
    }

}

?>