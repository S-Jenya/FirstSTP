<?php 
	require_once 'connection.php';
	session_start();
	$stmt = $pdo->query('SELECT name FROM Institution');
	//$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
	$retval[] = array();
	$i = 0;
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	  $retval[$i] = $row['name'];
	  $i++;
	}

ob_end_clean(); 
	echo(json_encode($retval, JSON_PRETTY_PRINT));
?>