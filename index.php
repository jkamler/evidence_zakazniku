<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else { //default action is index
  $action = "index";
}

//tomuhle nerozumim proc to funguje. V $action je retezec a kdyz za nej dam zavorku, tak mi spusti danou funkci - tzn. if pod tim nemusi byt
/*echo $action();*/

//vyber funkce podle $action
if ($action == "index") {
  return index();
}
if ($action == "klienti") {
  return klienti();
}
if ($action == "stavy") {
  return stavy();
}
if ($action == "insert_stav") {
  return insert_stav();
}
if ($action == "delete_stav") {
  return delete_stav();
}
if ($action == "edit_stav") {
  return edit_stav();
}
if ($action == "new_klient") {
  return new_klient();
}
if ($action == "insert_new_klient") {
  return insert_new_klient();
}


//basic page
function index() {
  $title = "Evidence zákazníků - Úvod";
  $data = "Rozhraní pro správu klientů a evidenci poznámek <br><br>Celkový počet evidovaných klientů: " . 21;
  require_once("app/view.php");
}

//page of clients
function klienti() {
  $title = "Evidence zákazníků - Evidence Klientů";
  $data = '
  <div class="input_form">
    <a href="index.php?action=new_klient"> + Nový klient</a>
  </div>';
  require_once("app/view.php");
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
  $JSfile = '<script src="js/jquery-validation/dist/jquery.validate.js"></script>
  <script>
    $("#insertstates").validate({
      rules: {
        nazev: "required",
        email: {
          email:true
        },
      },
      messages: {
        nazev: "Vložte prosím název klienta",
        email: "Zadejte platný email"
      }
    });
  </script>';
  require_once("app/view.php");
}

//calls function for insert
function insert_new_klient() {

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

function edit_stav() {
  include_once("app/class/DBClass.php");
  $myModel = new DBClass;
  echo "cau";
  //I am getting this variables via AJAX at edit.js
  $myModel->updateState($_GET["id"], $_GET["value"]);

//  header('Location: index.php?action=stavy');
}

?>
