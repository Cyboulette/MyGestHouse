<?php if(!$powerNeeded) { exit(); } ?> 

<?php
	if(!isset($utilisateur)){
        exit();
  }else{
    $id = $utilisateur->get('idUtilisateur');
    $nom = $utilisateur->get('nomUtilisateur');
    $prenom = $utilisateur->get('prenomUtilisateur');
    $email = $utilisateur->get('emailUtilisateur');
    $rang = $utilisateur->get('rang') ;
    $statut = $utilisateur->get('nonce');
  }
?>

<div class="col-xs-12 col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2"> 
<?php 
  	echo "<h1 class='page-header'>{$prenom} {$nom}</h1>";
  	echo "
  		<p><h4>Modification de l'utilisateur :</h4></p> 
  		</br>
    	
    	<form class='col-xs-offset-1' method='post' action='?controller=utilisateur&action=edited' enctype='multipart/form-data'>

      	<div class='form-group row'>
      		<label for='id_email' class='col-xs-6 col-form-label'>E_mail : </label>
      		<div class='col-xs-6'>
            <input type='email' name='email' id='id_email' value='{$email}'>        		
      		</div>
      	</div>

        <div class='form-group row'>
          <label for='id_nom' class='col-xs-6 col-form-label'>Nom de l'utilisateur : </label>
          <div class='col-xs-6'>
            <input type='text' value='{$nom}' name='nom' id='id_nom'>
          </div>
        </div>

        <div class='form-group row'>
          <label for='id_prenom' class='col-xs-6 col-form-label'>Prenom de l'utilisateur : </label>
          <div class='col-xs-6'>
            <input type='text' value='{$prenom}' name='prenom' id='id_prenom'>
          </div>
        </div>

        <div class='col-xs-6 col-sm-5 col-lg-3 clear-both-left'>
          <input type='submit' class='col-lg-offset-0 btn btn-s btn-success btn-block' value='Modifier'>
        </div>
    	</form>
    	</br>
    	</br>
    	</br>
 	  ";

  	echo "
		<h4>Changement de mot de passe :</h4>
		</br>

		<form class='col-xs-offset-1' method='post' action='' enctype='multipart/form-data'>

      <div class='form-group row'>
        <label for='id_ancienMDP' class='col-xs-6 col-form-label'>Ancien mot de passe : </label>
        <div class='col-xs-6'>
          <input type='password' name='ancienMDP' id='id_ancienMDP' placeholder='ancien mot de passe'>        		
        </div>
      </div>

      <div class='form-group row'>
      	<label for='id_nouveauMDP' class='col-xs-6 col-form-label'>Nouveau mot de passe : </label>
        <div class='col-xs-6'>
          <input type='password' name='nouveauMDP' id='id_nouveauMDP' placeholder='nouveau mot de passe'>
        </div>
      </div>

      <div class='form-group row'>
        <label for='id_nouveauMDPbis' class='col-xs-6 col-form-label'>Validation du mot de passe : </label>
        <div class='col-xs-6'>
          <input type='password' name='nouveauMDPbis' id='id_nouveauMDPbis' placeholder='validation du mot de passe'>
        </div>
      </div>

      <div class='col-xs-6 col-sm-5 col-lg-3 clear-both-left'>
        	<input type='submit' class='btn btn-s btn-success btn-block' value='Modifier'>
        <input type='hidden' value='{$id}' name='id'>
      </div>
    </form>
	";
?>
</div>

	