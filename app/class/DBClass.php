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
Function for DB queries

@param string $sql
SQL query

@return object
result of query
*/

  public function myQuery($sql) {
    try{
      require_once ("app/class/configClass.php");
      $conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
      if (!$conn) {
        throw new ExceptionConn;
      }
      mysqli_set_charset($conn, 'utf8');
      $result = mysqli_query($conn, $sql);
      if (!$result) {
        throw new ExceptionQuery;
      }
      self::logEvents($sql);
      return $result;
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
  Function for counting customes

  @return numeric
  Total count of customers
  */

  public function countCustomers() {
    $sql = "SELECT count(*) FROM klienti;";
    $result = self::myQuery($sql);
    $result = mysqli_fetch_array($result);
    return $result[0];
  }



    /*
    Function for selecting IDs of states, which are used in customers table

    @return array
    array of IDs of states, which are used in customers table
    */
    public function selectIdStates() {
      $sql = "SELECT DISTINCT id_stav FROM klienti;";
      $result = self::myQuery($sql);
      $id_states = array();
      while($row = mysqli_fetch_assoc($result)) {
        $id_states[] = $row['id_stav'];
      }
      return $id_states;
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
        <tbody>\n\t\t\t";

          while($row = mysqli_fetch_assoc($result)) {
            $myHTML .= "
            <tr>
              <td> " . date('d.m.Y', strtotime($row['datum_vl'])) ." </td>
              <td> " . $row['nazev'] ." </td> <td>" . $row['kontakt'] . " </td>
              <td> " . $row['email'] . " </td> <td> " . $row['stav'] . " </td>
              <td> " . $row['poznamka'] .   "
                <div id='edit'>
                  <div class='zmena' id='editace'>
                    <a href='index.php?action=edit_poznamka&id=" . $row['id_klient'] . "'>Edit</a>
                  </div>
                  <div class='zmena' id='mazani'>
                    <a href='index.php?action=delete_poznamka&id=" . $row['id_klient'] . "' onClick='return confirm(\"Opravdu smazat?\")'>X</a>
                  </div>
                </div>
              </td>
            </tr>\n\t\t\t";
          }

          $myHTML .="</tbody>
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
    Function for editing notes in customers management

    @param integer $id
    id of customer where I am going to edit note

    @param string $val
    string of updated note

    @return boolean || string
    0 - error || 1 - OK
    */

        public function editNote($id_klient, $note) {

          try{
      			require_once ("app/class/configClass.php");
      			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
      			if (!$conn) {
      				throw new ExceptionConn;
      			}
            mysqli_set_charset($conn, 'utf8');
            $sql = "UPDATE klienti SET poznamka='$note' WHERE id_klient = $id_klient;";

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
        Function for select string of edited note in customers management

        @param integer $id
        id of custome where I am going to edit note

        @return boolean || string
        0 - error || 1 - OK
        */

            public function selectNote($id) {
              require_once ("app/class/configClass.php");
        			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
        			if (!$conn) {
                self::logEvents("Chyba: nepovedlo se pripojit k DB: " . mysqli_connect_error() . ". File: " . $e->getFile() . ", line: " . $e->getLine());
        				exit("nepovedlo se pripojit k BD");
        			}
              mysqli_set_charset($conn, 'utf8');
              $sql = "SELECT poznamka FROM klienti WHERE id_klient = '$id';";
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
    Function for deleting notes in customers management

    @param integer $id
    id of custome where I am going to remove note

    @return boolean || string
    0 - error || 1 - OK
    */

        public function deleteNote($id) {

          try{
      			require_once ("app/class/configClass.php");
      			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
      			if (!$conn) {
      				throw new ExceptionConn;
      			}
            mysqli_set_charset($conn, 'utf8');
            $sql = "UPDATE klienti SET poznamka='' WHERE id_klient = $id;";

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

        //getting all used id_stav at klienti table
        $idStates = self::selectIdStates();

        //building HTML table
        $myHTML = "
  <div class='table'>
    <table>
      <thead>
        <tr>
          <th>Stav klienta</th>
        </tr>
      </thead>
      <tbody>
        ";

        while($row = mysqli_fetch_assoc($result)) {
          $myHTML .=
        "<tr>
          <td>" . $row['stav'] . "
            <div id='edit'>
              <div class='zmena_stavu' id='editace'>
                <a href='index.php?action=update_stav&id=" . $row['id_stav'] . "'>
                  Edit
                </a>
              </div>";


          //if state of customer is assigned to some customer, I can NOT delete this state
          if (!in_array($row['id_stav'], $idStates)) {
            $myHTML .= "    <div class='zmena' id='mazani'>
                <a href='index.php?action=delete_stav&id=" . $row['id_stav'] . "' onClick='return confirm("."\""."Opravdu smazat?\")'>
                  X
                </a>
              </div>";
          }
          $myHTML .= "
            </div>
          </td>
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
    Form for update name of state


    */

      public function updateStateForm($id_state) {
        try{
    			require_once ("app/class/configClass.php");
    			$conn = mysqli_connect(configClass::SERVERNAME, configClass::USERNAME, configClass::PASSWORD, configClass::DBNAME);
    			if (!$conn) {
    				throw new ExceptionConn;
    			}
          mysqli_set_charset($conn, 'utf8');

          $sql = "SELECT stav FROM stavy WHERE id_stav = '$id_state';";

          $result = mysqli_query($conn, $sql);
          if (!$result) {
            throw new ExceptionQuery;
          }

          $stav = mysqli_fetch_assoc($result);

          $myHTML = "
          <div class='input_form'>
            <form action='index.php?action=edit_stav' method='post'>
              Stav: <input type='text' name='stav' value='" . $stav['stav'] . "'>
              <input type='hidden' name='id' value='$id_state'>
              <input type='submit' value='Vložit'>
            </form>
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
    Function for updating state of customers at DB

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
