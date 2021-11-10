<?php

class Database 
{

	// properties = variabelen van een class
	private $host;
	private $database;
	private $username;
	private $password;
	private $pdo;

	// constructor

	public function __construct()
	{
		$this->host = 'localhost';
		$this->username = 'root';
		$this->password = '';
		$this->database = 'restaurantex';

		try{
			$dsn = 'mysql:host='.$this->host.';dbname='.$this->database;
			$this->pdo = new PDO($dsn, $this->username, $this->password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


		} catch (\PDOException $e) {
			echo "Database error <br>". $e->getMessage();
		}
	}


	// login admin
	public function login($gebruikersnaam, $wachtwoord)
	{
		try {

			$stmt = $this->pdo->prepare("SELECT * FROM users where gebruikersnaam = :gebruikersnaam AND wachtwoord = :wachtwoord");
			$stmt->bindParam(':gebruikersnaam',$gebruikersnaam);
			$stmt->bindParam(':wachtwoord',$wachtwoord);

			$stmt->execute();
			$rowCount = $stmt->rowCount();

			if ($row = $stmt->fetch()) {
				$_SESSION['gebruikersnaam'] = $gebruikersnaam;
				$_SESSION['rol'] = $row['rol'];
				return true;
			}
			else {
				return false;
			}
		}
		catch(PDOexception $e) {
			echo "failed:". $e->getMessage();
		}
	}


	public function select($query, $variables = [])
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$stmt->execute($variables);
			return $stmt;
		}
		catch(PDOexception $e) {
			echo "failed:". $e->getMessage();
		}
	}
	public function insert($statement, $placeholder, $locatie){

		try{
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare($statement);

			// Excecute the query

			$stmt-> execute($placeholder);

			$this->pdo->commit();

			header("location: $locatie");

		} catch(Exception $e){
			$this->pdo->rollback();
			echo "error message" . $e->getMessage();
		}
	}
	public function edit_or_delete($statement, $placeholder, $location){
		$stmt = $this->pdo->prepare($statement);
		// Excecute the query
		$stmt->execute($placeholder);
		header("location: $location");
	}
}