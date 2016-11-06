<?php

require_once File::build_path(array('controller', 'ControllerDefault.php'));

if(isset($_GET['controller']) && !empty(($_GET['controller'])))
{
    $controller = $_GET['controller'];
    $controller_class = 'Controller'.ucfirst($controller);


    if(file_exists(File::build_path(array('controller', $controller_class.'.php'))))
    {
        require_once File::build_path(array('controller', $controller_class.'.php'));

        if(class_exists($controller_class))
        {
            if(isset($_GET['action']) && !empty($_GET['action']))
            {
                $actionsExiste = get_class_methods($controller_class);
                $action = $_GET['action'];    // recup�re l'action pass�e dans l'URL

                if(in_array($action, $actionsExiste))
                {
                    $controller_class::$action(); // Appel de la m�thode statique $action de ControllerDefault
                } else {
                    ControllerDefault::error("L'action demand�e est impossible");
                }
            } else {
                ControllerDefault::index();
            }
        } else {
            ControllerDefault::error("Cette page n'existe pas");
        }
    } else {
        ControllerDefault::error("Cette fonctionnalit� n'est pas encore impl�ment�e");
    }
} else {
    ControllerDefault::index();
}
?>