<?php if(!$powerNeeded) { exit(); } ?>

<h1 class="page-header">Ajout d'un détail</h1>

<form class="form-horizontal" method="post" action="index.php?controller=adminDetails&action=addedDetail">
	<div class="form-group">
		<label for="id_nom" class="col-sm-2 control-label">Nom du détail à ajouter :</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" placeholder="EX : Repassage" name="nomDetail" id="id_nom">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-success" value="Ajouter">
			<a href="index.php?controller=adminDetails&action=details" class="btn btn-danger">Annuler</a>
		</div>
	</div>
</form>