<?php
class Velobd {

    private $db;

    public function __construct() {

		// Файл dbinfo.php возвращает массив для Подключения к БД
		$dbinfo = require_once 'config.php';
		
		// Подключение с проверкой
        try {
            $this->db = new PDO('mysql:host='.$dbinfo['host'].';port='.$dbinfo['port'].';dbname='.$dbinfo['dbname'].'',
			$dbinfo['login'], $dbinfo['password']);
			//echo "Connected successfully";
			return $this->db;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
		
			
	}


    // выборка таблицы из БД
		public function query($sql, $params = [])
		{
			// Подготовка запроса
			$stmt = $this->db->prepare($sql);
			
			// Обход массива с параметрами 
			// и подставляем значения
			if ( !empty($params) ) {
				foreach ($params as $key => $value) {
					$stmt->bindValue(":$key", $value);
				}
			}
			
			// Выполняя запрос
			$stmt->execute();
			// Возвращаем ответ
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

        // выборка всех записей
		public function getAll($table, $sql = '', $params = [])
		{
			return $this->query("SELECT * FROM $table" . $sql, $params);
		}

        // выборка одной строки
		public function getRow($table, $sql = '', $params = [])
		{
			$result = $this->query("SELECT * FROM $table" . $sql, $params);
			return $result[0]; 
		}

    //Определение существует ли таблица в БД (MySQL PDO)
    public function existsTable($table) {
        if ($this->db->query("DESCRIBE `".$table."`" )) {
            return "1"; //true
        } else {
            return "0"; //false
        }
    }

}
?>