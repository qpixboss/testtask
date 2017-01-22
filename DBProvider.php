<?php

class DBProvider {
	/**
	 * @var string
	 */
	private $dbName;
	/**
	 * @var string
	 */
	private $dbUser;
	/**
	 * @var string
	 */
	private $dbPassword;

	/**
	 * @var PDO
	 */
	private $db;

	/**
	 * DBProvider constructor.
	 * @param $dbName string
	 * @param $dbUser string
	 * @param $dbPassword string
	 */
	public function __construct($dbName, $dbUser, $dbPassword) {
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;

		$this->createConnection();
	}

	private function createConnection() {
		try {
			$this->db = new PDO('pgsql:dbname=' . $this->dbName . ';host=localhost;user=' .
				$this->dbUser . ';password=' . $this->dbPassword);
		} catch (PDOException $e) {
			die('Подключение не удалось: ' . $e->getMessage());
		}
	}

	/**
	 * @return integer
	 */
	public function getRowCount() {
		$result = $this->db->query('SELECT COUNT(*) Total FROM posts');
		$count = $result->fetch()[0];
		return $count;
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @return bool|PDOStatement
	 */
	public function selectQuery($limit = 0, $offset = 0) {
		if ($limit == 0 && $offset == 0) {
			$query = 'SELECT "ID", "CreationDate", "UserName", "Email", "PostData" FROM posts';
		} else {
			$query = 'SELECT "ID", "CreationDate", "UserName", "Email", "PostData" FROM posts LIMIT ' . $limit . ' OFFSET ' . $offset;
		}

		$result = $this->db->query($query);

		if (!($result instanceof PDOStatement)) {
			return false;
		}

		return $result;
	}

	/**
	 * @param $parameters array
	 * @return bool
	 */
	public function insertQuery($parameters) {
		var_dump($parameters);
		$query = $this->db->prepare('INSERT INTO posts ("CreationDate", "UserName", "Email", "PostData") VALUES (now(), ?, ?, ?)');
		if (!$query->execute($parameters)) return false;
		return true;
	}
}