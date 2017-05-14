<?php
class	ExceptionConn extends Exception {}
class	ExceptionInsert extends Exception {}
class ExceptionQuery extends Exception {}


class DBClass {

/*
Function for logging events to file

@param string $string
message
*/
  public function logEvents($string) {
    $timestamp = date('Y-m-d H:i:s');
    $file = 'app/events.txt';
    $current = file_get_contents($file);
    $current .= $timestamp . " " . $string . "\n";
    file_put_contents($file, $current);
  }




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
      self::logEvents($sql);
			mysqli_close($conn);
			return 1;
		}
		catch(ExceptionConn $e) {
			self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
			return 0;
		}
		catch(ExceptionInsert $e) {
			mysqli_close($conn);
      self::logEvents("Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
      return 0;
		}
		catch(Exception $e) {
      self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
			return 0;
		}
		catch(Error $e) {
      self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
			return 0;
    }
  }


  /*
  Function for listing all states of customers at states

  @return boolean || string
  0 - error || HTML document of states
  */

    public function listStates() {
      try{
  			require_once ("app/class/configClass.php");
  			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
  			if (!$conn) {
  				throw new ExceptionConn;
  			}
        mysqli_set_charset($conn, 'utf8');
        $sql = "SELECT * FROM stavy;";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
          throw new ExceptionQuery;
        }
        self::logEvents($sql);
        //building HTML table
        $myHTML = "
  <div class='table'>
    <table>
      <thead>
        <tr>
          <th>Stav klienta</th><th>Editace</th><th>Smazat</th>
        </tr>
      </thead>
      <tbody>
        ";

        while($row = mysqli_fetch_assoc($result)) {
          $myHTML .=
        "<tr>
          <td>" . $row['stav'] . "</td><td><span class='edit' id='" . $row['id_stav'] . "'>E</span></td> <td> <a href='index.php?action=delete_stav&id=" . $row['id_stav'] . "' onClick='return confirm("."\""."Opravdu smazat?\")'>S</a></td>
        </tr>
        ";
        }

        $myHTML .=
      "</tbody>
    </table>
  </div>
    ";

  			mysqli_close($conn);
  			return $myHTML;
  		}
  		catch(ExceptionConn $e) {
  			self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(ExceptionQuery $e) {
  			mysqli_close($conn);
  			self::logEvents("Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(Exception $e) {
        self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(Error $e) {
  			self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
      }
    }


    /*
    Function for creating select list of customers at client manegement

    @return boolean || string
    0 - error || HTML document of states
    */

      public function selectListStates() {
        try{
    			require_once ("app/class/configClass.php");
    			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
    			if (!$conn) {
    				throw new ExceptionConn;
    			}
          mysqli_set_charset($conn, 'utf8');
          $sql = "SELECT * FROM stavy;";
          $result = mysqli_query($conn, $sql);
          if (!$result) {
            throw new ExceptionQuery;
          }
//          self::logEvents($sql);
          //building select list
          $myHTML = "\n<select name='states' form='insertstates'>\n";
          while($row = mysqli_fetch_assoc($result)) {
            $myHTML .= "\t<option value='". $row['id_stav'] . "'>" . $row['stav'] . "</option>\n";
          }
          $myHTML .= "</select>\n";

    			mysqli_close($conn);
    			return $myHTML;

    		}
    		catch(ExceptionConn $e) {
    			self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
    			return 0;
    		}
    		catch(ExceptionQuery $e) {
    			mysqli_close($conn);
    			self::logEvents("Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
    			return 0;
    		}
    		catch(Exception $e) {
          self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
    			return 0;
    		}
    		catch(Error $e) {
    			self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
    			return 0;
        }
      }




/*
Function for deleting state of customers

@param integer $id
id of state which I am going to remove

@return boolean || string
0 - error || 1 - OK
*/

    public function deleteState($id) {

      try{
  			require_once ("app/class/configClass.php");
  			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
  			if (!$conn) {
  				throw new ExceptionConn;
  			}
        mysqli_set_charset($conn, 'utf8');
        $sql = "DELETE FROM stavy WHERE id_stav = $id;";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
          throw new ExceptionQuery;
        }
        self::logEvents($sql);
  			mysqli_close($conn);
  			return 1;
  		}
  		catch(ExceptionConn $e) {
  			self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(ExceptionQuery $e) {
  			mysqli_close($conn);
  			self::logEvents("Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(Exception $e) {
  			self::logEvents( "Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
  		}
  		catch(Error $e) {
  			self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
  			return 0;
      }

    }

    /*
    Function for updating state of customers

    @param integer $id
    id of state which I am going to remove

    @param string $value
    new value of state

    @return boolean || string
    0 - error || 1 - OK
    */

        public function updateState($id, $value) {

          try{
      			require_once ("app/class/configClass.php");
      			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
      			if (!$conn) {
      				throw new ExceptionConn;
      			}
            mysqli_set_charset($conn, 'utf8');

            $sql = "UPDATE stavy SET stav = '$value' WHERE id_stav = $id;";

            $result = mysqli_query($conn, $sql);
            if (!$result) {
              throw new ExceptionQuery;
            }
            self::logEvents($sql);

      			mysqli_close($conn);
      			return 1;
      		}
      		catch(ExceptionConn $e) {
      			self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
      			return 0;
      		}
      		catch(ExceptionQuery $e) {
      			mysqli_close($conn);
      			self::logEvents( "Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
      			return 0;
      		}
      		catch(Exception $e) {
            self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
      			return 0;
      		}
      		catch(Error $e) {
      			self::logEvents("Chyba: " . $e->getMessage() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
      			return 0;
          }

        }


}


?>
