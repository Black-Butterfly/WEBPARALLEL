<?php
	
?>

<div>
	<div class="row">
			<div class="col-sm-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Informations sur votre compte :</h3>
					</div>
					<div class="panel-body">
						<form method="post" autocomplete="off" action="control/user.php">	
							<div class="row">
								<div class="col-md-9">
									<table class="table">
									  <tbody>
									  <tr>
										<th><label for="name">Nom : </label></th>
										<th><input type="text" name="name"  placeholder="<?php echo $_SESSION['NOM']
										; ?>"/></th>
									  </tr>
									  <tr>
										<td><label for="surname">Prénom : </label></td>
										<td><input type="text" name="surname"  placeholder="<?php echo 
										$_SESSION['PRENOM']; ?>"/></td>
									  </tr>
									  <tr>
										<td><label for="email">*Adresse e-mail : </label></td>
										<td><input type="text" name="mail" placeholder="<?php echo 
										$_SESSION['EMAIL']; ?>" /></td>
									  </tr>
									  <tr>
										<td><label for="tel">*Numéro de telephone :</label></td>
										<td><input type="text" name="num" placeholder="<?php echo 
										$_SESSION['TEL']; ?>"/></td>
									  </tr>
									</tbody>
								  </table>
								</div>
							</div>
						<button name="valid" type="submit" class="btn btn-success">Valider 
								Modification</button>
						</form>
						<br/>
						<br/>
						</form>
						<form method="post" autocomplete="off" action="control/user.php">
							<div class="row">
								<div class="col-md-8">
								  <table class="table">
									  <tbody>
									  <tr>
										<th><label for="paswdOld">*Ancient mot de passe : </label></th>
										<th><input type="password" name="passwordOld" ></th>
									  </tr>
									  <tr>
										<td><label for="paswd">*Nouveau mot de passe : </label></td>
										<td><input type="password" name="password" ></td>
									  </tr>
									  <tr>
										<td><label for="paswd2">*Confirmation du nouveau mot de passe : </label></td>
										<td><input type="password" name="passwordValid" ></td>
									  </tr>
									</tbody>
								  </table>
								</div>
							</div>
							<button name="validMdp" type="submit" class="btn btn-success">Valider 
								Modification</button>
						</form>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>