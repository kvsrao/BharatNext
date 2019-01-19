<?php

require_once 'constants.php';

class Db {

    public static $dbcon = null; // stores database connection
    public $result; // stores the previous query result

    /*
      executes getConnection on new instance
     */

    public function __construct() {
        self::$dbcon = DB::getConnection();
    }

    /*
      returns a present database connection if present else creates and returns it;
     */

    public static function getConnection() {
        if (isset(self::$dbcon)) {
            return self::$dbcon;
        } else {
            try {
                return new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            } catch (PDOException $e) {
//                throw new Exception("Error : " . $e->getMessage());
                //die();
                return null;
            }
        }
    }

    /**
     * Executes query on a databse and stores the PDOStatement object in @var result and returns the same instance ($this)
     *
     * @var $query accepts a query string which will be converted into a 		prepare statement.
     * example : "SELECT * FROM users WHERE email = :email AND password = :password ";
     * @var $args accepts an array of arguments which accepts values that have to be substitued in placeholders of given query.
     * 	example : [':email'=>'something@gmail.com',':password'=>'*****']
     */
    public function query($query, array $args = null) {
        $db = self::$dbcon;
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // print_r($query);
        // print_r($args);
        try {
            $statement = $db->prepare($query);
            $statement->execute($args);
            $this->result = $statement;
            return $this;
        } catch (PDOException $e) {
//            echo "<br>Faild To Execute Query : " . $query . "<br> Reason : " . $e->getMessage();
//            die();
            return null;
        }
    }

    /*
     * 	Fetches the result of previously executed query in given type (		  	Default is PHP stdobj)

      example : $this->db->query("SELECT * FROM users WHERE email = :email ",[':email'=>$email])->get(); ->fetchs as an obj

      we can pass one of (assoc,obj,num,both);
     */

    public function get($type = 'obj') {
        switch ($type) {
            case 'assoc':
                return $this->result->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'obj':
                return $this->result->fetchAll(PDO::FETCH_OBJ);
                break;
            case 'num':
                return $this->result->fetchAll(PDO::FETCH_NUM);
                break;
            case 'both':
                return $this->result->fetchAll(PDO::FETCH_BOTH);
                break;
        }
    }

}

?>