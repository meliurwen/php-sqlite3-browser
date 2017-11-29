<html>

<head>
	<title>SQLite3 Database Browser</title>
</head>

<body>
	<?php

	$database = $_GET["database"];

	echo "<p><a href='databaseList.php'>Indietro</a></p>";
	
	//Connessione al database SQLITE3
    $db = new SQLite3($database);
    

	//INIZIO TABELLA
	echo "<TABLE border = '0'>";
	//Nome tabella
	echo "<TR><TD colspan='3' align='center'><B>Tabelle</B></TD></TR><TR>";
	//Prima riga con i nomi delle colonne
		echo "<TD align='center'><B>Nome</B></TD>";
		echo "<TD align='center'><B>Numero righe</B></TD>";
		echo "<TD align='center'><B>Numero colonne</B></TD>";
	echo "</TR>";
	//Il resto delle righe con il contenuto della tabella
	$tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
	while($table = $tablesquery->fetchArray(SQLITE3_ASSOC)){
		echo "<TR>";
		//Nome Tabella
		echo "<TD><a href='table.php?tabella=".$table['name']."&database=".$database."'>" .$table['name'] . "</a></TD>";
		//Numero Righe Tabella
		$rows = $db->query("SELECT COUNT(*) as count FROM ".$table['name'].";");
		$row = $rows->fetchArray();
		$numRows = $row['count'];
		echo "<TD align='center'>".$numRows."</TD>";
		//Numero Colonne Tabella
		$columns = $db->query("PRAGMA table_info(".$table['name'].");");
		$i = 0;
		while($column = $columns->fetchArray(SQLITE3_ASSOC)){
			$i = $i+1;
		};
		echo "<TD align='center'>".$i."</TD>";
		echo "</TR>";
	}
	echo "</TABLE>";
	//FINE TABELLA

?>

</body>
</html>
