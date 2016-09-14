<?php 

$tuesday = (date('w') == 2);

$locked = (file_get_contents('closed.lock') == date('Y-m-d'));

$allowed = ($tuesday and !$locked);

if ($allowed){
  define('CLIENT_ID', '2154556232.31561887542');
  define('CLIENT_SECRET', '0a787af94f57b0f2bdf7decb89f41812');
  define('REDIRECT_URI', (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : $_SERVER['REQUEST_SCHEME']).'://'.$_SERVER['SERVER_NAME'].'/');

  if(!isset($_SESSION)){
    session_start();
  }

  if(isset($_GET['code'])){
    $data = json_decode(file_get_contents('https://slack.com/api/oauth.access?code='.$_GET['code'].'&client_id='.CLIENT_ID.'&client_secret='.CLIENT_SECRET.'&redirect_uri='.REDIRECT_URI));
    if(isset($data->access_token)){
      $_SESSION['access_token'] = $data->access_token;
    }
  }
}

?>
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

    <script type="text/javascript">
    <?php
    if ($allowed){
      if(isset($_SESSION['access_token'])){ ?>
        window.token = '<?php echo $_SESSION['access_token']; ?>';
      <?php } else { ?>
        location.href = 'https://slack.com/oauth/authorize?client_id=<?php echo CLIENT_ID; ?>&redirect_uri=<?php echo REDIRECT_URI; ?>&scope=users:read';
      <?php } 
    }
    ?>
    </script>
      
    </script>

    <script type="text/javascript" src="main.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <style type="text/css">

      #user-pic {
        float: right;
        position: relative;
        top: -153px;
        left: 157px;
        width: 157px;
        height: 157px;
        border: 1px solid #398439;
        border-bottom: 0;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
      }

      h2 > i, #delete-order {
        font-size: 12px;
        border-bottom: 1px dashed #666;
      }

      #delete-order {
        position: relative;
        top: 30px;
      }

    </style>

  </head>
  <body>
    
    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="#">Home</a></li>
            <!-- <li role="presentation"><a href="#">About</a></li>
            <li role="presentation"><a href="#">Contact</a></li> -->
          </ul>
        </nav>
        <h3 class="text-muted">Terça da Pizza</h3>
      </div>

      <div class="jumbotron">
        <?php if($allowed) { ?>
        <h2>Escolha até 2 sabores abaixo</h2>
        <p>&nbsp;</p>
        <div id="selected" class="row">
          <div class="col-md-4 col-md-offset-4">
            <p>Sua preferência atual:</p>
            <table class="table table-striped table-condensed">
              <thead>
                <tr>
                  <th>
                    Sabor
                  </th>
                  <!-- <th>
                    Pedaços
                  </th> -->
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <label for="flavours">Sabor:</label>
            <select id="flavours" class="form-control"></select>
          </div>
          <div class="col-md-1">
            <label for="pieces"><!-- Pedaços: -->&nbsp;</label>
            <div class="input-group">
              <!-- <select id="pieces" class="form-control">
                <?php for($i = 1; $i <= 8; $i++){ ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
              </select> -->
              <input type="hidden" id="pieces" value="1" />
              <span class="input-group-btn">
                <a class="btn btn-md" id="add-pizza" href="javascript:;"><i class="icon glyphicon glyphicon-plus"></i></a>
              </span>
            </div>
          </div>
        </div>
        <p>&nbsp;</p>
        <div class="row">
          <div id="display" class="col-md-4 col-md-offset-4 text-center">
            <img src="" />
            <p></p>
          </div>
        </div>
        <p>
          <a id="delete-order" href="javascript:;">Tio, mudei de ideia, não quero mais pizza não... '-'</a>
          <a id="send-button" class="btn btn-lg btn-success pull-right" href="javascript:;" role="button">
            Manda ver! &nbsp;&nbsp;<i class="icon glyphicon glyphicon-thumbs-up"></i>
          </a>
          <img id="user-pic" src="">
        </p>
        <?php } elseif(! $tuesday) { 

          require_once 'messages.php';

        ?>

          <h2><?php echo $messages_only_tuesday[rand(0, sizeof($messages_only_tuesday) - 1)]; ?></h2>
        <?php } else { 

          require_once 'messages.php';

        ?>
          <h2><?php echo $messages_closed[rand(0, sizeof($messages_closed) - 1)]; ?></h2>
        <?php } ?>
      </div>

      <!-- <div class="row marketing">
        <div class="col-lg-6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>

        <div class="col-lg-6">
          <h4>Subheading</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Subheading</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Subheading</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>
      </div> -->

      <footer class="footer">
        <p>&copy; 2016 RedeAlumni, FTW.</p>
      </footer>

    </div> <!-- /container -->
    
  </body>
</html>
