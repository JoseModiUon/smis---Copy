<?php
print_r(PDO::getAvailableDrivers());

echo '<br/>';

// print_r(get_loaded_extensions());

echo '<br/>';

// $tns = " 
// (DESCRIPTION =
    // (ADDRESS_LIST =
      // (ADDRESS = (PROTOCOL = TCP)(HOST = 41.89.93.252)(PORT = 1521))
    // )
    // (CONNECT_DATA =
      // (SERVICE_NAME = devdb2)
    // )
  // )
       // ";
// $db_username = "smis";
// $db_password = "Smisdev20221";
// try {
    // $conn = new PDO("oci:dbname=" . $tns, $db_username, $db_password);
// } catch (PDOException $e) {
    // echo($e->getMessage());
// }


class PDOConnection {

    private $dbh;

    function __construct() {
        try {

            $server         = "41.89.93.252";
            $db_username    = "smis";
            $db_password    = "Smisdev2022";
            $service_name   = '';
            $sid            = "devdb2";
            $port           = 1521;
            $dbtns          = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = $server)(PORT = $port)) (CONNECT_DATA = (SERVICE_NAME = $service_name) (SID = $sid)))";

            //$this->dbh = new PDO("mysql:host=".$server.";dbname=".dbname, $db_username, $db_password);

            $this->dbh = new PDO("oci:dbname=" . $dbtns . ";charset=utf8", $db_username, $db_password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function select($sql) {
        $sql_stmt = $this->dbh->prepare($sql);
        $sql_stmt->execute();
        $result = $sql_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($sql) {
        $sql_stmt = $this->dbh->prepare($sql);
        try {
            $result = $sql_stmt->execute();
        } catch (PDOException $e) {
            trigger_error('Error occured while trying to insert into the DB:' . $e->getMessage(), E_USER_ERROR);
        }
        if ($result) {
            return $sql_stmt->rowCount();
        }
    }

    function __destruct() {
        $this->dbh = NULL;
    }
}

try{
	$dbh = new PDOConnection();
	$select_sql = "select * from user_role_privs";
	$dbh->select($select_sql);
}
catch(PDOException $e){
	// echo ($e->getMessage());
}