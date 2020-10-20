<?php
	require_once "connection.php";

	$stmt = $pdo->query("SELECT * FROM `institution`");
	//$row = $stmt->fetch(PDO::FETCH_ASSOC);

	echo "<table border='1' ><tr>";
	echo "<td>Roll No</td>";

	$retval = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	  	echo "<tr><td>";
	    echo ($row['name']);
	    echo "</td></tr>";
	}
	echo "</table>";
?>