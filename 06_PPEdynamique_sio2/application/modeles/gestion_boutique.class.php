<?php

//require_once '../../configs/mysql_config.class.php';

class GestionBoutique {

//<editor-fold defaultstate="collapsed" desc="région Champs">
//définition des champs
    /**
     * 
     * Objet de la classe PDO
     * @var PDO
     */
    private static $pdoCnxBase = null;

    /**
     * Objet de la classe PDOStatement
     * @var PDOStatement
     */
    private static $pdoStResults = null;
    private static $requete = ""; //texte de la requête
    private static $resultat = null; //résultat de la requête

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="région Méthodes">
//définition des méthodes
    /**
     * permet de se cinecter à la base de données
     */

    public static function seConnecter() {
        if (!isset(self::$pdoCnxBase)) { //S'il n'y a pas encore eu de connexion
            try {
                self::$pdoCnxBase = new PDO('mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' .
                        MysqlConfig::BASE, MysqlConfig::UTILISATEUR, MysqlConfig::MOT_DE_PASSE);
                self::$pdoCnxBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdoCnxBase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                self::$pdoCnxBase->query("SET CHARACTER SET utf8");
            } catch (Exception $e) {
                // l’objet pdoCnxBase a généré automatiquement un objet de type Exception
                echo 'Erreur : ' . $e->getMessage() . '<br />'; // méthode de la classe Exception
                echo 'Code : ' . $e->getCode(); // méthode de la classe Exception
            }
        }
    }

    public static function seDeconnecter() {
        self::$pdoCnxBase = null;
        //si one n'appelle pas la méthode, la déconnexion a lieu en fin de script
    }

    /**
     * retourne la liste des Catégories
     * @return type Tableau d'objets
     */
    public static function getLesTuplesByTable($table) {
        self::seconnecter();
        self::$requete = "SELECT * FROM $table";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    public static function getLesProduits() {
//    self::seconnecter();
//    self::$requete = "SELECT * FROM Produit";
//    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
//    self::$pdoStResults->execute();
//    self::$resultat = self::$pdoStResults->fetchAll();
//    
//    self::$pdoStResults->closeCursor();
//    
//    return self::$resultat;

        return self::getLesTuplesByTable('produit');
    }

    public static function getLesCategories() {
//    self::seconnecter();
//    self::$requete = "SELECT * FROM Categorie";
//    self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
//    self::$pdoStResults->execute();
//    self::$resultat = self::$pdoStResults->fetchAll();
//    
//    self::$pdoStResults->closeCursor();
//    
//    return self::$resultat;
        return self::getLesTuplesByTable('categorie');
    }

    public static function getLesProduitsByCategorie($libelleCategorie) {
        self::seConnecter();
        self::$requete = "SELECT * FROM Produit P,Categorie C where P.idCat = C.idCategorie
 AND LibelleCategorie = :libCateg";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('libCateg', $libelleCategorie);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        self::$pdoStResults->closeCursor();
        return self::$resultat;
    }

    public static function getProduitById($IdProduit) {
        self::seConnecter();
        self::$requete = "SELECT * FROM Produit P,Categorie C where P.idCat = C.idCategorie
 AND p.id = :IdProd";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('IdProd', $IdProduit);
        self::$pdoStResults->execute();
        //self::$resultat = self::$pdoStResults->fetchAll();
        self::$resultat = self::$pdoStResults->fetch();
        self::$pdoStResults->closeCursor();
        return self::$resultat;
        //</editor-fold>
    }

    public static function getNbProduits() {
        self::seConnecter();
        self::$requete = "SELECT Count(*) AS nbProduits FROM Produit";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();
        self::$pdoStResults->closeCursor();
        return self::$resultat->nbProduits;
    }

    /**
     * Ajoute une ligne dans la table Catégorie
     * @param type $libelleCateg Libellé de la Catégorie
     */
    public static function ajouterCategorie($libelleCateg) {
        self::seConnecter();
        self::$requete = "insert into Categorie(libelle) values(:libelle)";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('libelle', $libelleCateg);
        self::$pdoStResults->execute();
    }

    public static function ajouterProduit($nom, $description, $prix, $image, $idCategorie) {
        self::seConnecter();
        self::$requete = "insert into Produit(nom, description, prix, image, idCategorie) values('$nom', '$description', $prix,'$image', $idCategorie)";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
    }

    /**
     * Vérifie si l'utilisateur est un administrateur présent dans la base
     * @param type $login Login de l'utilisateur
     * @param type $passe Passe de l'utilisateur
     * @return type Booléen
     */
    public static function isAdminOK($login, $passe) {
        self::seConnecter();
        self::$requete = "SELECT * FROM Utilisateur where login=:login and passe=:passe";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('login', $login);
        self::$pdoStResults->bindValue('passe', sha1($passe));
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();
        self::$pdoStResults->closeCursor();
        if ((self::$resultat != null) and ( self::$resultat->isAdmin))
            return true;
        else
            return false;
    }

}
?> 
<?php
// Tests de services (méthodes) de la classe GestionBoutique
//----------------------------------------------------------
////test de la méthode getLesCategories()
//$lesCategories = GestionBoutique::getLesCategories();
////var_dump($lesCategories);
////
//////test de la méthode getLesProduitsByCategorie()
////$lesProduits = GestionBoutique::getLesProduitsByCategorie('enfants');
////var_dump($lesProduits);
////
//////Compréhension de la méthode fetchAll() de la classe PDOStatement :
//$leProduit = GestionBoutique::getProduitById(1);
////var_dump($leProduit);
////
//////Nombre produits
//$NombreProduit = GestionBoutique::getNbProduits();
//
?>
<!--<p> il y a <?php // echo $NombreProduit ?> produits dans la boutique </p>-->
<?php
////var_dump($NombreProduit);
////test ajoutcatégorie
////GestionBoutique::ajouterCategorie('electro');
////var_dump(GestionBoutique::getLesCategories());
////test ajout produit
////GestionBoutique::ajouterProduit('Darude - Sandstorm', 'test', 6.5, 'test', 4);
////var_dump(GestionBoutique::getLesProduits());
////EXERCICE 1
//
////echo "Produit retourné : </br>
////    ----------------- </br>
//   id :$leProduit->id </br>
//   nom : $leProduit->nom</br>
//   description :$leProduit->description </br>
//   prix : $leProduit->prix</br>
//   fichier de l'image : $leProduit->image</br>" ;
//
//// EXERCICE 2
//  foreach ($lesCategories as $uneCategorie)
//{
//    echo $uneCategorie -> libelle."</br>";
//  }
//Test de connexion
//GestionBoutique::seConnecter();
?>
