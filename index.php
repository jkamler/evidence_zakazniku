<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "index";
}

//tomuhle nerozumim proc to funguje. V $action je retezec a kdyz za nej dam zavorku, tak mi spusti danou funkci - tzn. if pod tim nemusi byt
/*echo $action();*/

/*vyber funkce podle $action*/
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

//basic page
function index() {
  $data = "Rozhraní pro správu klientů a evidenci poznámek <br><br>Celkový počet evidovaných klientů: " . 21;
  require_once("app/view.php");
}

//page of clients
function klienti() {
  $data = "klienti";
  require_once("app/view.php");
}

//page of states
function stavy() {
  $insertForm = '
  <form action="index.php?action=insert_stav" method="post">
  Nový stav <input type="text" name="stav">
  <input type="submit">
  </form>';
  $data = $insertForm;
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

function

?>
