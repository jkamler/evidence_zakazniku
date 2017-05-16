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
  Function for counting customes

  @return numeric $count
  Total count of customers
  */
    public function countCustomers() {
			require_once ("app/class/configClass.php");
			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
			if (!$conn) {
        self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
				exit("nepovedlo se pripojit k BD");
			}
      mysqli_set_charset($conn, 'utf8');
      $sql = "SELECT count(*) FROM klienti;";
      $result = mysqli_query($conn, $sql);
      if (!$result) {
        self::logEvents("Chyba: nepovedlo se provest dotaz: " . $sql . "<br>" . mysqli_error($conn) . ". File: " . $e->getFile() . ", line: " . $e->getLine());
				exit("nepovedlo se provest dotaz");
      }
			mysqli_close($conn);
      $result = mysqli_fetch_array($result);
      return $result[0];
    }


    /*
    Function for listing customers

    @param string $condition
    condition for searchng records in table klienti

    @return boolean || string
    0 - error || HTML document of states
    */

      public function listCustomers($condition) {
        try{
    			require_once ("app/class/configClass.php");
    			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
    			if (!$conn) {
    				throw new ExceptionConn;
    			}
          mysqli_set_charset($conn, 'utf8');
          $sql = "SELECT id_klient, datum_vl, nazev, kontakt, email, id_stav, stav, poznamka FROM klienti NATURAL JOIN stavy WHERE $condition;";
          $result = mysqli_query($conn, $sql);
          if (!$result) {
            throw new ExceptionQuery;
          }
          self::logEvents($sql);
          //building HTML table
/*
          - Datum vložení,
          - Název firmy,
          - Kontaktní osoba,
          - E-mail,
          - Stav,
          - Poznámka (možnost editace / smazání)
*/        //select list of states

          $myHTML = "
      <table>
        <thead>
          <tr>
            <th>Datum vložení</th><th>Název firmy</th><th>Kontaktní osoba</th><th>E-mail</th><th>Stav</th><th>Poznámka</th>
          </tr>
        </thead>
        <tbody>
          ";

          while($row = mysqli_fetch_assoc($result)) {
//            $myHTML .= "<tr> <td> " . $row['datum_vl'] ." </td> <td> " . $row['nazev'] ." </td> <td>" . $row['kontakt'] . " </td> <td> " . $row['email'] . " </td> <td> " . $row['stav'] . " </td> <td><input type='text' name='poznamka' class='poznamka' value='" . $row['poznamka'] . "' readonly> </td> </tr>";
            $myHTML .= "<tr> <td> " . date('d.m.Y', strtotime($row['datum_vl'])) ." </td> <td> " . $row['nazev'] ." </td> <td>" . $row['kontakt'] . " </td> <td> " . $row['email'] . " </td> <td> " . $row['stav'] . " </td> <td><input type='text' name='poznamka' id='" . $row['id_klient'] . "' class='poznamka' value='" . $row['poznamka'] . "' readonly> </td> </tr>";

          }

          $myHTML .=
        "</tbody>
      </table>
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
  Function for inserting new customer into DB

  @param string $nazev
  Value of new customer

  @param string $kontakt
  Value of new contact person

  @param string $email
  Value of contact email

  @param string $telefon
  Value of new contact telephone

  @param string $states
  Value of ID of state

  @param string $poznamka
  Value of new comment

  @return boolean
  0 - error, 1 - OK
  */

    public function insertNewCustomer($nazev, $kontakt, $email, $telefon, $states, $poznamka) {
      try{
  			require_once ("app/class/configClass.php");
  			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
  			if (!$conn) {
  				throw new ExceptionConn;
  			}
        $sql = "INSERT INTO klienti (nazev, kontakt, email, telefon, id_stav, poznamka) VALUES ('$nazev', '$kontakt', '$email', '$telefon', $states, '$poznamka');";
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
          $sql = "SELECT * FROM stavy ORDER BY 2 ASC;";
          $result = mysqli_query($conn, $sql);
          if (!$result) {
            throw new ExceptionQuery;
          }
//          self::logEvents($sql);
          //building select list
          $myHTML = "\n<select id='selectListStates' name='states' form='insertstates'>\n";
          $myHTML .= "\t<option value='' selected>Vyber stav</option>\n";
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
