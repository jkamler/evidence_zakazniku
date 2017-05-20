<?php
//controller

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//$action exist and is not empty
if ((isset($_GET["action"])) && ($_GET['action']) != "" ) {
  $action = $_GET["action"];
} else { //default action is index
  $action = "index";
}


//tomuhle nerozumim proc to funguje. V $action je retezec a kdyz za nej dam zavorku, tak mi spusti danou funkci - tzn. funguje to podobne jako ty ify podtim
/*echo $action();*/

//selecting function by action value
if ($action == "index") {
  return index();
} else if ($action == "klienti") {
  return klienti();
} else if ($action == "stavy") {
  return stavy();
} else if ($action == "insert_stav") {
  return insert_stav();
} else if ($action == "delete_stav") {
  return delete_stav();
} else if ($action == "edit_stav") {
  return edit_stav();
} else if ($action == "update_stav") {
  return update_stav();
} else if ($action == "new_klient") {
  return new_klient();
} else if ($action == "insert_new_klient") {
  return insert_new_klient();
} else if ($action == "delete_poznamka") {
  return delete_poznamka();
} else if ($action == "edit_poznamka") {
  return edit_poznamka();
} else if ($action == "update_poznamka") {
  return update_poznamka();
} else {
  return index();
}




//basic page
function index() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $count = $myModel->countCustomers();

  $title = "Evidence zákazníků - Úvod";
  $data = "Rozhraní pro správu klientů a evidenci poznámek <br><br>Celkový počet evidovaných klientů: " . $count;
  $data .= "<br><br>Specifikace zadání:<br>

1) Evidence klientů - seznam<br>
- Datum vložení,<br>
- Název firmy,<br>
- Kontaktní osoba,<br>
- E-mail,<br>
- Stav,<br>
- Poznámka (možnost editace / smazání)<br>
<br>
Filtry pro vyhledávání<br>
- Fulltext (celé nebo část slova)<br>
- Podle stavu (zobrazení pouze klientů s přiřazeným konkrétním stavem)<br>
<br>
Přidat klienta<br>
- Název<br>
- Kontaktní osoba<br>
- Tel.<br>
- E-mail<br>
- Poznámka<br>
- Stav<br>
<br>
2) Stavy klientů<br>
- seznam nadefinovaných stavů (možnost editace / smazání)<br>
<br>
Přidat stav<br>
- název stavu<br>
<br>
3) Doplňkové<br>
- Přihlašování do rozhraní pod heslem";

  require_once("app/view.php");
}

//page of clients
function klienti() {
//fulltext_val and states_val - send by AJAX in customers.js thrue POST
//if variable fulltext_val or states_val exist at POST
  if (isset($_POST['fulltext_val']) || (isset($_POST['states_val']))) {
    isset($_POST['fulltext_val']) ? $fulltext_val = htmlspecialchars($_POST['fulltext_val']) : $fulltext_val = FALSE;
    isset($_POST['states_val']) ? $states_val = $_POST['states_val'] : $states_val = FALSE;

    // searching only in displayed colums kontakt email poznamka datum_vl stav
    //fulltext box and select list are filled
    if (($fulltext_val != FALSE) && ($states_val != FALSE)) {
      $condition = " (nazev LIKE '%$fulltext_val%' OR kontakt LIKE '%$fulltext_val%' OR email LIKE '%$fulltext_val%' OR poznamka LIKE '%$fulltext_val%' OR datum_vl LIKE '%$fulltext_val%') AND (id_stav = '$states_val')";
    }
    //fulltext box neither select list are filled
    if (($fulltext_val == FALSE) && ($states_val == FALSE)) {
      $condition = 1;
    }
    //only fulltext box is filled
    if (($fulltext_val != FALSE)  && (!$states_val)) {
      $condition = " nazev LIKE '%$fulltext_val%' OR kontakt LIKE '%$fulltext_val%' OR email LIKE '%$fulltext_val%' OR poznamka LIKE '%$fulltext_val%' OR datum_vl LIKE '%$fulltext_val%'";
    }
    //only select list is selected
    if ((!$fulltext_val) && ($states_val != FALSE)) {
      $condition = " id_stav = '$states_val'";
    }
    include_once("app/class/DBClass.php");
    $myModel = new DBClass;
    $myCustomerList = $myModel->listCustomers($condition);
    echo $myCustomerList;
    exit;
  }

  include_once("app/class/DBClass.php");
  $myModel = new DBClass;

  // first load of web page
  $title = "Evidence zákazníků - Evidence Klientů";

  //insert new customer
  $data = "
  <div id='edit'>
    <a class='zmena' href='index.php?action=new_klient'> + Nový klient</a>
  </div>";

  //search customers
  $selectList = $myModel->selectListStates();
  $data .= "<div id='search_box'>Vyhledávání<br>
    <input type='text' name='get_val' id='find' placeholder='Hledané slovo'>
    Stav: $selectList
  </div>";
  $myCustomerList = $myModel->listCustomers("1");

  $data .= "<div class='table' id='result_table'>\n$myCustomerList\n</div>";

  $JSfile = "<script src='js/customers.js'></script>\n";

  require_once("app/view.php");
}

//deleting notes
function delete_poznamka() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $mySelectList = $myModel->deleteNote($_GET['id']);

  header('Location: index.php?action=klienti');
}

//editing notes
function edit_poznamka() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  //getting string of old note
  $myNote = $myModel->selectNote($_GET['id']);

  $title = "Evidence zákazníků - editace poznámky";

  $data = "
  <form action='index.php?action=update_poznamka' method='post'>
    <label> Editace poznámky:</label>
    <input type='text' name='note' value='$myNote' size='50'>
    <input type='hidden' name='id' value='" . $_GET['id'] . "'>
    <input type='submit' value='Uložit'>
  </form>\n";

  require_once("app/view.php");
}

//update note
function update_poznamka() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $note = htmlspecialchars($_POST['note']);
  $id_klient = $_POST['id'];
  $mySelectList = $myModel->editNote($id_klient, $note);
//  require_once("app/view.php");
  header('Location: index.php?action=klienti');
}


//loads form for inserting new client
function new_klient() {
  //getting Select list of states
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $mySelectList = $myModel->selectListStates();

  $title = "Evidence zákazníků - Nový klient";
  $data = "<h2>Zadejte nového klienta:</h2><br>";
  $data .= '
  <div class="input_form">
    <div class="input_container">
      <form action="index.php?action=insert_new_klient" method="post" id="insertstates">
        <label>Název klienta</label> <input type="text" name="nazev">
        <label>Kontaktní osoba</label> <input type="text" name="kontakt">
        <label>Kontaktní email</label> <input type="text" name="email">
        <label>Telefon</label> <input type="text" name="telefon">
        <label>Stav klienta</label>';
  $data .= $mySelectList;
  $data .= '<label>Poznámka</label> <input type="text" name="poznamka">
        <input type="submit" value="Vložit">
      </form>
    </div>
  </div>';
  $JSfile = "\t<script src='js/jquery-validation/dist/jquery.validate.js'></script>\n\t\t<script src='js/validate.js'></script>";
  require_once("app/view.php");
}

//calls function for insert of new client
function insert_new_klient() {
  $nazev = isset($_POST['nazev']) ? htmlspecialchars($_POST['nazev']) : "";
  $kontakt = isset($_POST['kontakt']) ? htmlspecialchars($_POST['kontakt']) : "";
  $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
  $telefon = isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : "";
  $states = isset($_POST['states']) ? $_POST['states'] : "0";
  $poznamka = isset($_POST['poznamka']) ? htmlspecialchars($_POST['poznamka']) : "";

  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $data .= $myModel->insertNewCustomer($nazev, $kontakt, $email, $telefon, $states, $poznamka);

  header('Location: index.php?action=klienti');
}

//page of states
function stavy() {
  $JSfile = "<script src='js/edit.js'></script>\n";
  $title = "Evidence zákazníků - Stavy zákazníků";
  $insertForm = '
  <div class="input_form">
    <form action="index.php?action=insert_stav" method="post">
      Nový stav: <input type="text" name="stav">
      <input type="submit" value="Vložit">
    </form>
  </div>';
  $data = $insertForm;
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $data .= $myModel->listStates();
  require_once("app/view.php");
}

//inserting new state to table stavy
function insert_stav() {
  //check if value stav is set and string is not empty
  if ((isset($_POST["stav"])) and strlen($_POST["stav"]) > 0) {
    $state = htmlspecialchars($_POST["stav"]);
    include_once("app/class/DBClass.php");
    $myModel = new DBClass;
    $myModel->insertNewState($state);
    //reload page with new state
    header('Location: index.php?action=stavy');
  } else { //string is empty - redirect
    header('Location: index.php?action=stavy');
  }
}

//deleting state of customer
function delete_stav() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $myModel->deleteState($_GET["id"]);
  header('Location: index.php?action=stavy');
}

//editing state of customer
function edit_stav() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  $myModel->updateState($_POST["id"], $_POST["stav"]);
  header('Location: index.php?action=stavy');
}

//loading form for update state name
function update_stav() {
  $title = "Evidence zákazníků - Editace stavu zákazníka";
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  //getting string of old state
  $data = $myModel->updateStateForm($_GET['id']);
  require_once("app/view.php");
}

?>
