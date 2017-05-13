
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Evidence zákazníků</title>
  <link rel="stylesheet" href="css/reset.css" type="text/css">
  <link rel="stylesheet" href="css/main.css" type="text/css">
  <script src="js/test.js"></script>
</head>
<body>
  <div id="main_wrapper">
    <div id="menu">
      <ul>
        <li><a href="index.php?action=index">Úvod</a></li>
        <li><a href="index.php?action=klienti">Evidence klientů</a></li>
        <li><a href="index.php?action=stavy">Editace stavů</a></li>
      </ul>
    </div>
    <div id="content">
      <?php
        echo $data;
      ?>
    </div>
  </div>
</body>
</html>
