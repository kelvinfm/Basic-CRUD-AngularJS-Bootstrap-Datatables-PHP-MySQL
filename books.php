<?php	

/**
*** Description: Simple CRUD using AngularJS, Bootstrap, Datatables, PHP and MariaDB/MySQL
*** 
*** Author: Kelvin Fernandez
*** Date: 2019-04-04
*** email: kelvinfm@yahoo.com
*** github: https://github.com/kelvinfm
**/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once("db_pdo.php");


if(isset($_GET['method'])) {
	$method = $_GET['method'];
	switch($method) {
		case 'All':
		case 'Create':
			echo json_encode(Books::$method());
			break;
		case 'Find':
		case 'Delete':
		case 'Update':
			if(isset($_GET['id'])) {
				echo json_encode(Books::$method($_GET['id']));
			}
			break;
	}
}


class Books {
	
	static function All() {
		$sql = "SELECT * FROM books WHERE 1";
		$db = DB::Connect();
		$stmt = $db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
		
	}
	
	static function Create() {
		$post = json_decode(file_get_contents('php://input'), true);
		$set = self::Buld_set($post);
		$sql = "INSERT INTO books SET {$set}";
		$db = DB::Connect();
		$db->beginTransaction();
		$stmt = $db->prepare($sql);
		foreach ($post as $key => &$value) {
			$stmt->bindParam($key, $value);
		}
		if($stmt->execute() AND $stmt->rowCount()>0) {
			$lastinsertid = $db->lastInsertId(); 
			$db->commit();  
			$array = array('status' => "success", 'msg' => "SUCCESS: \nBooks ".$lastinsertid." was Created", 'id'=>$lastinsertid);
		} else {
			$db->rollBack();
			$lastinsertid = 0;
			$array = array('status' => "warning", 'msg' => "WARNING: \nBooks was Not Created", 'id'=>$lastinsertid);
		}
		return $array;
	}
	
	static function Find($id) {
		$sql = "SELECT * FROM books WHERE id=:id";
		$db = DB::Connect();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		return $stmt->fetch();
	}

	static function Delete($id) {
		$sql = "DELETE FROM books WHERE id = :id";
		$db = DB::Connect();
		$db->beginTransaction();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);
		if($stmt->execute() AND $stmt->rowCount()>0) {
			$db->commit(); 
			$array = array('status' => "success", 'msg' => "SUCCESS: \nBooks {$id} was Deleted");
		} else {
			$db->rollBack();
			$array = array('status' => "warning", 'msg' => "WARNING: \nBooks {$id} was Not Deleted");
		}
		return $array;
	}

	static function update($id) {
		$post = json_decode(file_get_contents('php://input'), true);
		$set = self::Buld_set($post);
		$sql = "UPDATE books SET {$set} WHERE id = :id";
		$db = DB::Connect();
		$db->beginTransaction();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id);
		foreach ($post as $key => &$value) {
			$stmt->bindParam($key, $value);
		}
		if($stmt->execute() AND $stmt->rowCount()>0) {
			$db->commit();  
			$array =array('status' => "success", 'msg' => "SUCCESS: \nBooks {$id} was Updated");
		} else {
			$db->rollBack();
			$array =array('status' => "warning", 'msg' => "WARNING: \nBooks {$id} was Not Updated");
		}
		return $array;
	}	
	
	static function Buld_set($post) {
		$string = '';
		$i = 0;
		foreach($post as $row => $value) {
			$string .= $row." = :".$row;
			$string .= ($i < count($post)-1) ? ", " : "";
			$i++;
		}
		return $string;	
	}

	//static function Connect() {
	//	$host = 'localhost';
	//	$name = 'test';
	//	$user = 'root';
	//	$pass = '';
	//	$db = new PDO("mysql: host={$host}; dbname={$name}", $user, $pass);
	//	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	//	return $db;
	//}
	
}

?>



