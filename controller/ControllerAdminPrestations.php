<?php 
	class ControllerAdminPrestations extends ControllerAdmin {
		public static function prestations($message = null){
			$powerNeeded = self::isAdmin();
			$view = 'listPrestations';
			$pagetitle = 'Administration - Options du site';
			$template = 'admin';
			$tab_allPrestation = ModelPrestation::selectAll();
			require_once File::build_path(array("view","main_view.php"));
		}

		public static function addPrestation(){
			$powerNeeded = self::isAdmin();
			$view = 'addPrestation';
			$pagetitle = 'Administration - Ajout prestation';
			$template = 'admin';
			require_once File::build_path(array("view","main_view.php"));
		}

		public static function addedPrestation(){
			$powerNeeded = self::isAdmin();
			if(isset($_POST['nomPrestation']) && isset($_POST['prix'])){
				if($_POST['nomPrestation']!=null && $_POST['prix']!=null){
					if($_POST['prix']>= 0){
						$laPrestation = array(
							'idPrestation' => null,
							'nomPrestation' => $_POST['nomPrestation'],
							'prix' => $_POST['prix'],
						);
						$save = ModelPrestation::save($laPrestation);
						if($save != false) {
							$message = '<div class="alert alert-success">Prestation ajoutée avec succès !</div>';
						}else{
							$message = '<div class="alert alert-danger">Echec de l\'ajout de la prestation !</div>';
						}
					}else{
						$message = '<div class="alert alert-danger">Vous ne pouvez pas proposer un prix negatif !</div>';
					}
				}else{
					$message = '<div class="alert alert-danger">vous ne pouvez pas laisser un champ vide !</div>';
				}
			}else{
				$message = '<div class="alert alert-danger">Nous navons pas pu recuperer vos choix !</div>';
			}
			self::prestations($message);
		}

		public static function editPrestation(){
			$powerNeeded = self::isAdmin();
			if(isset($_GET['idPrestation']) && $_GET['idPrestation']!=null){
				$prestation = ModelPrestation::select($_GET['idPrestation']);
				if($prestation!=false){
					$view = 'editPrestation';
					$pagetitle = 'Administration - modifier ue prestation';
					$template = 'admin';
					require_once File::build_path(array("view", "main_view.php"));
				}else{
					$message = '<div class="alert alert-danger">cette prestation n\'existe plus !</div>';
					self::prestations($message);
				}
			}else{
				$message = '<div class="alert alert-danger">vous ne pouvez pas modifier une prestation sans connaitre son ID !</div>';
				self::prestations($message);
			}
		}

		public static function editedPrestation(){
			$powerNeeded = self::isAdmin();
			if(isset($_POST['idPrestation']) && $_POST['idPrestation']!=null) {
				$prestation = ModelPrestation::select($_POST['idPrestation']);
				if($prestation!=false){
					if(isset($_POST['nomPrestation']) && isset($_POST['prix'])
						&& $_POST['nomPrestation']!=null && $_POST['prix']!=null){
						if($_POST['prix']>= 0){
							$id = $_POST['idPrestation'];
							$nom = $_POST['nomPrestation'];
							$prix = $_POST['prix'];
							$dataPrestation = array(
								'nomPrestation' => $nom,
								'prix' => $prix,
								'idPrestation' => $id,
							);
							$update = ModelPrestation::update_gen($dataPrestation, 'idPrestation');
							if($update != false) {
								$message = '<div class="alert alert-success">Prestation modifiée avec succès !</div>';
							} else {
								$message = '<div class="alert alert-danger">Echec de la modification de la prestation !</div>';
							}
						}else{
							$message = '<div class="alert alert-danger">Vous ne pouvez pas proposer un prix negatif !</div>';
						}
					}else{
						$message = '<div class="alert alert-danger">vous ne pouvez pas laisser un champ vide !</div>';
					}
				}else{
					$message = '<div class="alert alert-danger">cette prestation n\'existe plus !</div>';
				}
			}else{
				$message = '<div class="alert alert-danger">vous ne pouvez pas modifier une prestation sans connaître son ID !</div>';
			}
			self::prestations($message);
		}

		public static function managePrestations(){
			$powerNeeded = self::isAdmin();
			if(isset($_GET['idChambre']) && $_GET['idChambre']!=NULL){
				$chambre = ModelChambre::select($_GET['idChambre']);
				if($chambre!=null){
					$view = 'prestationFor';
					$pagetitle = 'Administration - Editeur de chambre';
					$template = 'admin';
					$idChambre = $_GET['idChambre'];
					$tab_prestation = ModelPrestation::selectAllByChambre($_GET['idChambre']);
					$tab_allPrestation = ModelPrestation::selectAll();
					require_once File::build_path(array("view", "main_view.php"));
				}else{
					$message = '<div class="alert alert-danger">Cette chambre n\'existe plus !</div>';
					ControllerAdminChambres::chambres($message);
				}
			}else{
				$message = '<div class="alert alert-danger">Vous ne pouvez modifier les prestations d\'une chambre sans connaître son ID !</div>';
				ControllerAdminChambres::chambres($message);
			}
		}

		public static function managedPrestation(){
			$powerNeeded = self::isAdmin();
			if(isset($_POST['idChambre']) && $_POST['idChambre']!=null){
				$idChambre = $_POST['idChambre'];
				$prestation = $_POST['prestations'];
				$update = true;
				$update = ModelPrestation::deleteAllByChambre($idChambre); //TODO vérifier si true
				if ($prestation!=null) {
					foreach ($prestation as $key => $value) {
						$update = ModelPrestation::saveByChambre($idChambre, $prestation[$key]);
					}
				}
				if($update != false) {
					$message = '<div class="alert alert-success">Prestation modifiée avec succès !</div>';
				} else {
					$message = '<div class="alert alert-danger">Echec de la modification de la prestation !</div>';
				}
			}else{
				$message = '<div class="alert alert-danger">Vous ne pouvez modifier les prestations d\'une chambre sans connaître son ID !</div>';
			}
			ControllerAdminChambres::chambres($message);
		}
	}
?>