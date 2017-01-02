<?php 
	class ControllerAdminUtilisateurs extends ControllerAdmin {

		public static function utilisateurs($message = null) {
			$powerNeeded = self::isAdmin();
			$view = 'listUsers';
			$pagetitle = 'Administration - Liste des utilisateurs';
			$template = 'admin';

			if(isset($_GET['mode']) && $_GET['mode']!=null){
				switch ($_GET['mode']) {
		            case 'admin': $tab_utilisateurs = ModelUtilisateur::selectCustom('rang', '3'); $mode='admin'; break;
		            case 'membre': $tab_utilisateurs = ModelUtilisateur::selectCustom('rang', '2'); $mode='membre'; break;
		            case 'visiteur': $tab_utilisateurs = ModelUtilisateur::selectCustom('rang', '1'); $mode='visiteur'; break;
		            case 'all': $tab_utilisateurs = ModelUtilisateur::selectAll(); $mode='all'; break;
		            case 'nonValide': $tab_utilisateurs = ModelUtilisateur::selectNonValide(); $mode='nonValide'; break;
		            default: $tab_utilisateurs = ModelUtilisateur::selectAll(); $mode='all'; break;
        		}
			}else{
				$mode='all';
				$tab_utilisateurs = ModelUtilisateur::selectAll();
			}
			require_once File::build_path(array("view","main_view.php"));
		}

		public static function read(){
			$powerNeeded = self::isAdmin();
			if(isset($_GET['idUtilisateur']) && $_GET['idUtilisateur']!=null){
				$utilisateur = ModelUtilisateur::select($_GET['idUtilisateur']);
				if($utilisateur!=false){
					$view = 'readUser';
					$pagetitle = 'Administration - un utilisateur';
					$template = 'admin';
					require_once File::build_path(array("view", "main_view.php"));
				}else{
					$message = '<div class="alert alert-danger">cet Utilisateur n\'existe plus !</div>';
					self::utilisateurs($message);
				}
			}else{
				$message = '<div class="alert alert-danger">Votre requette n\'a pas pu aboutire !</div>';
				self::utilisateurs($message);
			}
		}

		public static function edit(){
			$powerNeeded = self::isAdmin();
			$view = 'editUser';
			$pagetitle = 'Administration - modification de l\'utilisateur';
			$template = 'admin';
			
			if(isset($_GET['idUtilisateur'])){
				$utilisateur = ModelUtilisateur::select($_GET['idUtilisateur']);

				if($utilisateur!=false){
					require_once File::build_path(array("view","main_view.php"));
				}else{
					$message = '<div class="alert alert-danger">Cet utilisateur n\'existe plus !</div>';
					self::utilisateurs($message);
				}
			}else{
				$message = '<div class="alert alert-danger">Vous ne pouvez pas modifier un utilisateur sans connaitre son ID !</div>';
				self::utilisateurs($message);
			}
		}

		public static function edited(){
			$powerNeeded = self::isAdmin();
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['rang'])){

				if($_POST['nom']!=null && $_POST['prenom']!=null && $_POST['email']!=null && $_POST['rang']!=null){

					$lutilisateur = array(
						'idUtilisateur' => $_POST['id'],
						'emailUtilisateur' => $_POST['email'],
						'nomUtilisateur' => $_POST['nom'],
						'prenomUtilisateur' => $_POST['prenom'],
						'rang' => $_POST['rang'],
					);
					$update = ModelUtilisateur::update_gen($lutilisateur, 'idUtilisateur');
					if($update!=false){
						$message = '<div class="alert alert-success">Utilisateur modifiées avec succès !</div>';
					}else{
						$message = '<div class="alert alert-danger">Nous n\'avons pas pu procéder à la mise a jour de l\'utilisateur!</div>';
					}
				}else{
					$message = '<div class="alert alert-danger">Vous ne pouvez pas laisser de champs vide !</div>';
				}   
			}else{
				$message = '<div class="alert alert-danger">Vous ne pouvez pas acceder à la modification sans passer par la vue de modification !</div>';
			}
			self::utilisateurs($message);
		}

		public static function add(){
			$powerNeeded = self::isAdmin();
			$view = 'addUser';
			$pagetitle = 'Administration - Liste des utilisateurs';
			$template = 'admin';
			require_once File::build_path(array("view", "main_view.php"));
		}

		public static function added(){
			$powerNeeded = self::isAdmin();
			// echo "<pre>";
			// 	print_r($_POST);
			// echo "</pre>";
			if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) 
				&& isset($_POST['rang']) && isset($_POST['motDePasse']) && isset($_POST['motDePassebis']) ){

				if($_POST['nom']!=null && $_POST['prenom']!=null && $_POST['email']!=null 
					&& $_POST['rang']!=null && $_POST['motDePasse']!=null && $_POST['motDePassebis']!=null
					&& !ctype_space($_POST['nom']) && !ctype_space($_POST['prenom']) && !ctype_space($_POST['email'])){

					$email = strip_tags($_POST['email']);
		            $password = strip_tags($_POST['motDePasse']);
		            $passwordBis = strip_tags($_POST['motDePassebis']);
		            $nom = strip_tags($_POST['nom']);
		            $prenom = strip_tags($_POST['prenom']);
		            $rang = $_POST['rang'];

		            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		               	$checkUser = ModelUtilisateur::selectCustom('emailUtilisateur', $email);
		               	if($checkUser == false) {
		                    if($password == $passwordBis) {
			            		$lutilisateur = array(
									'idUtilisateur' => NULL,
	                                'prenomUtilisateur' => $prenom,
	                                'nomUtilisateur' => $nom,
	                                'emailUtilisateur' => $email,
	                                'password' => password_hash($password, PASSWORD_DEFAULT),
	                                'rang' => $rang,
	                                'nonce' => null
								);
								$save = ModelUtilisateur::save($lutilisateur);
								if($save!=false){
									$message = '<div class="alert alert-success">Utilisateur ajoutée avec succès !</div>';
								}else{
									$message = '<div class="alert alert-danger">Nous n\'avons pas pu procéder à la création de l\'utilisateur !</div>';
								}  
			                }else{
			                    $message='<div class="alert alert-danger">Les mots de passe ne sont pas les même !</div>';
			                }
		               	} else {
		                  	$message='<div class="alert alert-danger">Cette adresse mail est deja utiliser !</div>';
		               	}
		            } else {
		              	$message='<div class="alert alert-danger">L\'adresse e-mail renseignée est invalide !</div>';
		            }
				}else{
					$message = '<div class="alert alert-danger">Vous ne pouvez pas laisser de champ vide ou avoir un prix ou une seperficie inferieur a zero !</div>';
				}   
			}else{
				$message = '<div class="alert alert-danger">Vous ne pouvez pas acceder à la modification sans passer par la vue de modification !</div>';
			}
			self::utilisateurs($message);
		}
	}
?>