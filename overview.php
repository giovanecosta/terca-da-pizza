<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="utf-8">

    <script src="https://code.jquery.com/jquery-1.12.2.min.js" integrity="sha256-lZFHibXzMHo3GGeehn1hudTAP3Sc0uKXBXAzHX1sjtk=" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <title>Terça da Pizza Overview</title>

    <script type="text/javascript">
      
      $(function(){
        $('#log').hide();
        $('#show-log').click(function(){
          $('#log').toggle();
        });
      });

    </script>

    <style type="text/css">
      h2 > i {
        cursor: help;
        font-size: 20px;
        border-bottom: 1px dashed #666;
        color: #000;
      }
    </style>

  </head>
  <body>
  <?php

  $day = date('Y-m-d');

  if(isset($_GET['lock'])){
    if($_GET['lock'] == 'true'){
      file_put_contents('closed.lock', $day);
    } elseif ($_GET['lock'] == 'false') {
      file_put_contents('closed.lock', ' ');
    }
  }

  $locked = (file_get_contents('closed.lock') == $day);

  $path = dirname(__FILE__).'/db/main.sqlite3';

  $db = new SQlite3($path);

  $results = $db->query(sprintf("SELECT * FROM pedidos WHERE reference_day='%s'", $day));

  $users = [];
  $pizzas = [];
  $log = [];
  $sumFlavours = 0;

  while ($row = $results->fetchArray()){
    $user = $row['user'];
    $flavour = $row['flavour'];
    // For now is always 1
    //$pieces = $row['quantity'];

    $users[] = $user;

    if(!isset($pizzas[$user])){
      $pizzas[$user] = [];
    }
    $pizzas[$user][] = $flavour;

    $log[] = $row;
  }

  $selectedFlavours = [];
  foreach ($pizzas as $user => $flavours) {
    
    foreach ($flavours as $flavour) {
      if(!isset($selectedFlavours[$flavour])){
        $selectedFlavours[$flavour] = 0;
      }
      $selectedFlavours[$flavour] += (3 - sizeof($flavours));
    }

    $sumFlavours += 2;
  }

  $order = [];
  $half = [];
  $rest = [];

  $users = array_unique($users);

  $qtd = count($users);

  $pieces = $qtd * 3.5;

  $qtdPizzas = ceil($pieces / 8);

  if ($qtdPizzas % 2 == 1) {
    $qtdPizzas++;
  }

  $qtdHalfs = $qtdPizzas * 2;

  foreach ($selectedFlavours as $flavour => $weight) {
    $pct = $weight / $sumFlavours;
    $half[$flavour] = round($qtdHalfs * $pct);
  }

  $lastHalf = null;
  foreach ($half as $flavour => $qtd) {
    $total = floor($qtd / 2);
    if($total > 0){
      $order[$flavour] = $total;
    }
    if($qtd % 2 == 1){
      if($lastHalf){
        $order['1/2 '.$lastHalf.' 1/2 '.$flavour] = 1;
        $lastHalf = null;
      } else {
        $lastHalf = $flavour;
      }
    }
  }

  if($lastHalf){
    $rest[$lastHalf] = 1;
  }

  /*foreach ($pizzas as $flavour => $pieces) {
    $remaining = $pieces;
    if ($remaining >= 8){
      $order[$flavour] = floor($pieces / 8);
      $remaining = $pieces % 8;
    }
    if($remaining >= 4){
      $half[] = $flavour;
      $remaining -= 4;
    }
    if($remaining > 0){
      $rest[$flavour] = $remaining;
    }
  }

  if(sizeof($half) % 2 == 1){
    $flavour = array_pop($half);
    if(!isset($rest[$flavour])){
      $rest[$flavour] = 0;
    }
    $rest[$flavour] += 4;
  }

  for ($i = 0; $i < sizeof($half); $i += 2){
    $f1 = $half[$i];
    $f2 = $half[$i + 1]; 
    $order['1/2 '.$f1.' 1/2 '.$f2] = 1;
  }
*/

  ?>
  <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="#">Home</a></li>
            <?php if($locked){ ?>
            <li role="presentation"><a href="overview.php?lock=false">Reabrir</a></li>
            <?php } else { ?>
            <li role="presentation"><a href="overview.php?lock=true">Finalizar Hoje</a></li>
            <?php } ?>
          </ul>
        </nav>
        <h3 class="text-muted">Terça da Pizza Overview</h3>
      </div>

      <div class="jumbotron">
        <h2>Escolha da galera (<i title="<?php echo implode($users, ', '); ?>" id="users" ><?php echo sizeof($users); ?> pessoas</i>) :</h2>
        <p>&nbsp;</p>
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <table class="table table-striped table-condensed">
              <thead>
                <tr>
                  <th>
                    Qtd.
                  </th>
                  <th>
                    Sabor
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($order as $flavour => $quantity) { ?>
                  <tr>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $flavour; ?></td>
                  </tr>
                <?php } ?>
                <?php if(sizeof($rest) > 0){ ?>
                  <tr>
                    <td colspan="2">
                      <b>Metades restantes:</b>
                    </td>
                  </tr>
                  <?php foreach ($rest as $flavour => $quantity) { ?>
                    <tr>
                      <td><?php echo $quantity; ?></td>
                      <td><?php echo $flavour; ?></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="col-md-2 col-md-offset-5">
          Total: <?php echo $qtdPizzas ?> Pizzas
          </div>
          <div class="col-md-2 col-md-offset-5">
            <button id="show-log">Log</button>
          </div>
          <div id="log" class="col-md-8 col-md-offset-2">
            <table class="table table-striped table-condensed">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Sabor
                  </th>
                  <th>
                    Qtd.
                  </th>
                  <th>
                    Hora do pedido
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($log as $line) { ?>
                  <tr>
                    <td><?php echo $line['user']; ?></td>
                    <td><?php echo $line['flavour']; ?></td>
                    <td><?php echo $line['quantity']; ?></td>
                    <td><?php echo $line['ordered_at']; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

      <footer class="footer">
        <p>&copy; 2016 RedeAlumni, FTW.</p>
      </footer>

    </div> <!-- /container -->
  </body>
</html>