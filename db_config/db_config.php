<?php
	

	class DB_CONFIG
	{
		// Baza ma'lumotlari
		private const HOSTNAME = 'localhost';
		private const USERNAME = 'dataBaseUserName';
		private const PASSWORD = 'dataBasePassword';
		private const DATABASE_NAME = 'dataBaseName';

		protected $conn;

		protected $dataSet;
		protected $sqlQuery;

		function __construct()
		{
			$this->conn = mysqli_connect($this::HOSTNAME, $this::USERNAME, $this::PASSWORD, $this::DATABASE_NAME);
			if (!$this->conn) {
				echo "MYSQLI connect error!";
				return;
			}
			return true;
		}

		// Ma'lumotlar bazasi bilan bog'lanishni uzish
		public function disConnect()
		{
			$this->conn = NULL;
			$this->dataSet = NULL;
			$this->sqlQuery = NULL;
		}

		// Ma'lumot bazasi bilan aloqada vujudga kelishi mumkin bo'lgan xatolikdan qochib qutulish
		protected function escapeString($value)
		{
			return mysqli_real_escape_string($this->conn, $value);
		}

		// Ma'lumotlar bazasidan barcha ma'lumotlarni olish
		public function selectAll($tableName)
		{
			$this->sqlQuery = 'SELECT * FROM ' . $this::DATABASE_NAME . '.' . $tableName;
			$this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
			return $this->dataSet;
		}

		// Ma'lumotlar bazasidan biror shartga ko'ra ma'lumot olish
		public function selectWhere($tableName, $condition, $extra="")
		{
			$this->sqlQuery = 'SELECT * FROM ' . $tableName . ' WHERE ';
			if (gettype($condition) == "array") {
	        	foreach ($condition as $keys => $values) {
		        	foreach ($values as $key => $value) {
		        		if ($key !== 'cn') {
		        			$this -> sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
			        		$this->sqlQuery .= $this->escapeString($values[$key]);
			        		$this->sqlQuery .= "' and ";
		        		}
		        	}
		        }
		        $this->sqlQuery = substr($this->sqlQuery, 0,strlen($this->sqlQuery)-4);
	        }else{
	        	$this->sqlQuery .= $condition;
	        }
	        $this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
	        return $this->dataSet;
		}

		// Ma'lumotlar bazasiga ma'lumot kiritish
		public function insertInto($tableName,$data=[])
		{
			$this->sqlQuery = 'INSERT INTO ' . $tableName;
			$columns = '(';
			$values = "(";
			foreach ($data as $key => $value) {
				$columns .= $this->escapeString($key) . ',';
				$values .= "'";
				$values .= $this->escapeString($value) . "',";
			}
			$columns = substr($columns, 0, strlen($columns)-1);
			$values = substr($values, 0, strlen($values)-1);
			$columns .= ')';
			$values .= ')';
			$this->sqlQuery .= $columns . ' VALUES ' . $values;
			return (mysqli_query($this->conn, $this->sqlQuery)) ? true : false;
		}

		// Ma'lumotlar bazasidan biror ma'lumotni shartga ko'ra o'chirish
		public function deleteWhere($tableName, $condition=[],$extra="")
		{
			$this->sqlQuery = 'DELETE FROM ' . $tableName . ' WHERE ';
			foreach ($condition as $values) {
				foreach ($values as $key => $value) {
					if ($key != 'cn') {
						$this->sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
						$this->sqlQuery .= $this->escapeString($values[$key]) . "'";
						$this->sqlQuery .= ' and ';
					}
				}
			}
			$this->sqlQuery = substr($this->sqlQuery, 0, strlen($this->sqlQuery)-4);
			$this->sqlQuery .= $extra;
			$this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
			return ($this->dataSet) ? true : false;
		}

		// Ma'lumotlar bazisadan biror shartga ko'ra ma'lumotni yangilash
		public function updateWhere($tableName, $values=[], $condition=[], $extra="")
		{
			$this->sqlQuery = 'UPDATE ' . $tableName . ' SET ';
			foreach ($values as $key => $value) {
				$this->sqlQuery .= $this->escapeString($key);
				$this->sqlQuery .= "='";
				$this->sqlQuery .= $this->escapeString($value);
				$this->sqlQuery .= "',";
			}
			$this->sqlQuery = substr($this->sqlQuery, 0,strlen($this->sqlQuery)-1);
			$this->sqlQuery .= ' WHERE ';
			foreach ($condition as $keys => $val) {
				if ($keys != 'cn') {
					$this->sqlQuery .= $this->escapeString($keys) . $condition['cn'] = "='";
					$this->sqlQuery .= $this->escapeString($condition[$keys]) . "' ";
					$this->sqlQuery .= " and ";
				}
			}
			$this->sqlQuery = substr($this->sqlQuery, 0, strlen($this->sqlQuery)-4);
			$this->sqlQuery .= $extra;
			return (mysqli_query($this->conn, $this->sqlQuery)) ? true : false;
		}

		// Ma'lumotlar bazasiga qo'lda so'rov yuborish
		public function withSqlQuery($query)
		{
			return mysqli_query($this->conn, $this->escapeString($query));
		}
	}
?>
