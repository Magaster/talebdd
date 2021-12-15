<nav id="menu_gauche">
    <h1> Catégories </h1>
    <ul>
        <?php
        foreach (VariablesGlobales::$lesCategories as $uneCategorie) {
            ?>
            <li>
                <a href=index.php?cas=afficherProduits&categorie=<?php echo $uneCategorie->LibelleCategorie; ?> > <?php echo $uneCategorie->LibelleCategorie; ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</nav>