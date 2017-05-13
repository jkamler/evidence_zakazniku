<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["action"])) {
  $action = $_GET["action"];
} else {
  $action = "index";
}

//tomuhle nerozumim proc to funguje. V $action je retezec a kdyz za nej dam zavorku, tak mi spusti danou funkci - tzn. 3x if pod tim nemusi byt
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

function index() {
  $data = "Rozhraní pro správu klientů a evidenci poznámek <br><br>Celkový počet evidovaných klientů: " . 21;
  require_once("app/view.php");
}

function klienti() {
  $data = "klienti";
  require_once("app/view.php");
}

function stavy() {
  $insertForm = '<form action="test.php" method="post"> Nový stav <input type="text" name="name"><input type="submit"></form>';
  $data = $insertForm;
  require_once("app/view.php");
}

function insert_stav() {
  
}

?>
