<?php
class	ExceptionConn extends Exception {}
class	ExceptionInsert extends Exception {}


class DBClass {

/*
Function for inserting new state of customer into DB

@param string $value
Value of new state

@return boolean
0 - error, 1 - OK
*/

  public function insertNewState($value) {
    try{
			require_once ("app/class/configClass.php");
			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
			if (!$conn) {
				throw new ExceptionConn;
			}
      $sql = "INSERT INTO stavy(stav) VALUES ('$value');";
      mysqli_set_charset($conn, 'utf8');
			if (!mysqli_query($conn, $sql)) {
				throw new ExceptionInsert;
			}
			mysqli_close($conn);
			return 1;
		}
		catch(ExceptionConn $e) {
			echo "Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine();
			return 0;
		}
		catch(ExceptionInsert $e) {
			mysqli_close($conn);
			echo "Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine();
			return 0;
		}
		catch(Exception $e) {
			echo "Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine();
			return 0;
		}
		catch(Error $e) {
			echo "Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine();
			return 0;
    }
  }

}


?>
