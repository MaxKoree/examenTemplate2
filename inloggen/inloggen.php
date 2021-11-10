<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../style.css">
	<title>Inloggen</title>
</head>
<body>
<div class="topnav">
  <a href="../index.php">Home</a>
</div>
<?php

include '../Database/database.php';

if(isset($_POST['username']) && isset($_POST['password'])) {
	// probeer in te loggen

	

	$db = new \Database;

	if($db->login($_POST['username'], $_POST['password'])) {
		header("location: ../hoofdpagina.php");
	}
	else {
		// niet ingelogd

		?>
		<div>Verkeerde gegevens</div>
		<?php
	}

}

?>

	<form class="login" action="" method="POST">	
		<input type="text" name="username" placeholder="Gebruikersnaam">
		<input type="password" name="password" placeholder="Wachtwoord">
		<button type="submit">Inloggen</button>
	</form>

</body>
</html>