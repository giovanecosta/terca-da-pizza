<?php

header('Content-Type: application/json');

$now = date('Y-m-d H:i:s');
$day = date('Y-m-d');

$locked = (file_get_contents('closed.lock') == $day);

$path = dirname(__FILE__).'/db/main.sqlite3';

if(!$locked){

	if(isset($_POST['user'])){

		$user = $_POST['user'];

		try {
			$db = new SQlite3($path);
			$begun = false;
			
			if($_POST['action'] == 'add'){

				$db->query('BEGIN;');
				$begun = true;

				$db->query(sprintf("DELETE FROM pedidos WHERE user='%s' AND reference_day='%s';", $user, $day));

				$fields = '(user, flavour, quantity, ordered_at, reference_day)';
				foreach ($_POST['preferences'] as $pref){
					$values = sprintf("('%s', '%s', %d, '%s', '%s')", $user, $pref['pizza'], $pref['pieces'], $now, $day);
					$result = $db->query(sprintf('INSERT INTO pedidos %s VALUES %s ;', $fields, $values));
					if(! $result){
						throw new Exception("Insert Error ".$values, 2);
					}
				}
				$db->query('COMMIT;');
				$message = 'Pedido realizado, '.$_POST['user'].'! Agora é só esperar um pouquinho e ser feliz!';

			} elseif ($_POST['action'] == 'delete'){

				$db->query(sprintf("DELETE FROM pedidos WHERE user='%s' AND reference_day='%s';", $user, $day));
				$message = 'Tudo bem, meu jovem, o tio já deletou seus pedidos. Até a próxima!';

			}
		} catch (Exception $e) {
			if($begun){
				$db->query('ROLLBACK;');
			}
			$message = 'Erro ao salvar pedido :/ ('.$e.')'; 
		}

		respond_message($message);
	}

} else {
	respond_message('Pedidos fechados...');
}


function respond($data){
	echo json_encode($data);
}

function respond_message($message){
	respond(['message' => $message]);
}

if (isset($_GET['user'])) {

	$user = $_GET['user'];

	$db = new SQlite3($path);

	if($_GET['action'] == 'list'){

		$results = $db->query(sprintf("SELECT * FROM pedidos WHERE user='%s' AND reference_day='%s'", $user, $day));

		$rows = [];

		while ($row = $results->fetchArray()) {
			$rows[] = $row;
		}
		
		respond($rows);
	}
}
