<?php
/**
 * This is the main view, here we call all the other views
 */
?>


<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?php echo $pagetitle; ?></title>

        <?php
        require File::build_path(array("assets", "css", "styles.php"));
        ?>

    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top menuHaut">
            <div class="container">
                <div class="navbar-header">
                    // ...
                    <a class="navbar-brand visible-xs" href="index.php">Index</a>
                </div>

            </div>

            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="logoBrand"><a href="index.php">So'CAP</a></li>
                    <li <?php ControllerDefault::active('index', ''); ?>><a href="index.php">Accueil</a></li>



<!--                    <li-->
<!--                    --><?php //ControllerDefault::active('produit', ''); ?><!--<!-->
<!--                    <a href="index.php?controller=produit&action=readAll">Produits</a></li>-->
<!--                    --><?php
//                    if(!isset($_SESSION['login'])) {
//                        ?>
<!--                        <li-->
<!--                            --><?php //ControllerDefault::active('utilisateur', 'connect'); ?><!-->
<!--                            <a href="index.php?controller=utilisateur&action=connect">Connexion</a></li>-->
<!--                        <li-->
<!--                            --><?php //ControllerDefault::active('utilisateur', 'register'); ?><!-->
<!--                            <a href="index.php?controller=utilisateur&action=register">Inscription</a></li>-->
<!--                    --><?php //} else { ?>
<!--                        <li-->
<!--                            --><?php //ControllerDefault::active('commande', ''); ?><!-->
<!--                            <a href="index.php?controller=commande&action=readAll">Vos commandes</a></li>-->
<!--                        <li-->
<!--                            --><?php //ControllerDefault::active('utilisateur', 'profil'); ?><!-->
<!--                            <a href="index.php?controller=utilisateur&action=profil">Mon Profil</a></li>-->
<!--                        <li-->
<!--                            --><?php //ControllerDefault::active('utilisateur', 'disconnect'); ?><!-->
<!--                            <a href="index.php?controller=utilisateur&action=disconnect">D�connexion</a></li>-->
<!--                    --><?php //} ?>

                </ul>
            </div>

        </nav>

        <?php
            if(isset($powerNeeded) && $powerNeeded == true)
            {
                if ($view != 'index'){
                    echo '<div class="container page" >';
                }
                    $filepath = File::build_path("view" , static::$object, "$view.php");
                    require $filepath;

                if($view != 'index'){
                    echo '</div>';
                }
            }else{
                echo '<div class="container page"><div class="alert alert-danger">Vous ne poss�dez pas les droits pour acc�der � cette page</div></div>';
            }
        ?>


        <?php require File::build_path(array("assets", "js", "js.php")); ?>

        <footer class="footer">
            <div class="container">
                <p class="text-muted">Bonjour Aurevoir</p>
            </div>
        </footer>
    </body>
<html>





