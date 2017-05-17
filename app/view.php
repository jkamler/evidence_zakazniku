
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="css/reset.css" type="text/css">
  <link rel="stylesheet" href="css/main.css" type="text/css">
  <link rel="stylesheet" href="js/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
  <script src='js/jquery.tablesorter.min.js'></script>
  <script>
  $(document).ready(function() {
    // call the tablesorter plugin
    $("table").tablesorter({
        // sort on the first column, order asc
        sortList: [[0,0]]
    });
  });
  </script>
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
        <div id="footer">
          paticka
        </div>
      </div>
      <?php
      //if custom JavaScript needed
      if(isset($JSfile)) {
        echo $JSfile;
      }
      ?>
  </body>
</html>
