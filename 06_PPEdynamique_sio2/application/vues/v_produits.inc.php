<!-- CORPS de la page-->
<section>

    <?php
    foreach (VariablesGlobales::$lesProduits as $unProduit) {
    ?>
        <article>
            <img src="<?php echo Chemins::IMAGES_PRODUITS."/".VariablesGlobales::$libelleCategorie."/".$unProduit->ImageProduit;?>" alt="photo" />
            <aside>              
                <h3><?php echo $unProduit->LibelleProduit;?></h3>
                <!--<p>(<?php echo VariablesGlobales::$libelleCategorie;?>)</p>-->
                <h3><?php echo $unProduit->PrixHTProduit;?></h3>
                <a href="#">
                    <img src="public/images/ajouter_panier.png" title="Ajouter au panier"/> </a>
            </aside>

        </article>

    <?php
      }
    ?>
</section>