<?php
/**
 * Vienkārša PDO klase
 * @author Autors : Miks Zvirbulis (twitter.com/MiksZvirbulis)
 * @version Versija : 2.0
 * 1.0 - Palaista pirmā versija, kura ļauj savienoties vienai datubāzei un izsūtīt izmantotākās komandas
 * 2.0 - Pievienota opcija ar konstrukēšanu palaist dažādas datubāzes uz dažādiem mainīgajiem
 */
class db{
	# Datubāzes hostētāja adrese
	protected $host;
	# Lietotājvārds, lai piekļūtu datubāzei
	protected $username;
	# Parole, lai piekļūtu datubāzei
	protected $password;
	# Datubāzes nosaukums
	protected $database;

	# Savienojuma mainīgais - nemainīt
	protected $connection;

	# @bool noklusējuma "savienojuma" statuss - true/false (lūdzams neaiztikt un atstāt false pēc noklusējuma)
	public $connected = false;

	# @bool vai vēlies, lai uzrāda kļūdas? - true/false
	private $errors = true;

	function __construct($db_host, $db_username, $db_password, $db_database){
		global $c;
		try{
			if($c['page']['debug'] === true){
				$this->errors = true;
			}else{
				$this->errors = false;
			}
			$this->host = $db_host;
			$this->username = $db_username;
			$this->password = $db_password;
			$this->database = $db_database;
			$this->connected = true;

			$this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$this->connected = false;
			if($this->errors === true){
				return $this->error($e->getMessage());
			}else{
				return false;
			}
		}
	}

	function __destruct(){
		$this->connected = false;
		$this->connection = null;
	}

	public function error($error){
		echo '<div class="alert alert-danger">' . $error . '</div>';
	}

	public function fetch($query, $parameters = array()){
		if($this->connected === true){
			try{
				$query = $this->connection->prepare($query);
				$query->execute($parameters);
				return $query->fetch();
			}
			catch(PDOException $e){
				if($this->errors === true){
					return $this->error($e->getMessage());
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function fetchAll($query, $parameters = array()){
		if($this->connected === true){
			try{
				$query = $this->connection->prepare($query);
				$query->execute($parameters);
				return $query->fetchAll();
			}
			catch(PDOException $e){
				if($this->errors === true){
					return $this->error($e->getMessage());
				}else{
					return false;
				}
			}
		}else{
			return $this->error("Savienojums nav izveidots!");
		}
	}

	public function count($query, $parameters = array()){
		if($this->connected === true){
			try{
				$query = $this->connection->prepare($query);
				$query->execute($parameters);
				return $query->rowCount();
			}
			catch(PDOException $e){
				if($this->errors === true){
					return $this->error($e->getMessage());
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function insert($query, $parameters = array()){
		if($this->connected === true){
			try{
				$query = $this->connection->prepare($query);
				$query->execute($parameters);
			}
			catch(PDOException $e){
				if($this->errors === true){
					return $this->error($e->getMessage());
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function update($query, $parameters = array()){
		if($this->connected === true){
			return $this->insert($query, $parameters);
		}else{
			return false;
		}
	}

	public function delete($query, $parameters = array()){
		if($this->connected === true){
			return $this->insert($query, $parameters);
		}else{
			return false;
		}
	}

	public function tableExists($table){
		if($this->connected === true){
			try{
				$query = $this->count("SHOW TABLES LIKE '$table'");
				return ($query > 0) ? true : false;
			}
			catch(PDOException $e){
				if($this->errors === true){
					return $this->error($e->getMessage());
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
}