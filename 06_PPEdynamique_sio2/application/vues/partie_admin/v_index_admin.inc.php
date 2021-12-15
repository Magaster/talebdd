<section>
    <div class="titre">
        Administration du site (Accès réservé)</br>
        - Bonjour <?php echo $_SESSION['login_admin']; ?> -
    </div> 
    <a href="">Gestion des catégories</a></br>
    <a href="">Gestion des produits</a></br>
    <p>
        <a href="index.php?cas=seDeconnecter">Déconnexion (<?php echo $_SESSION['login_admin'] ?>)</a>
    </p>
</section>
