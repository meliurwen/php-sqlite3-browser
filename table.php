<html>

<head>
	<title>SQLite3 Database Browser</title>
</head>

<body>
	<?php

	$filename = $_GET['database'];
	$database = $filename;
	$tabella = $_GET['tabella'];

	//phpinfo();
	//exit;

	echo "<p><a href='tableList.php?database=".$database."'>Indietro</a></p>";

	//Connessione al database
	class MyDB extends SQLite3
	{
		function __construct($filename)
		{
			$this->open($filename);
		}
	}

	$db = new MyDB($filename);
	if(!$db){
		echo "Impossibile aprire il database<BR />";
	} else {
		echo "Database aperto con successo<BR />";
	}

	
	//INIZIO TABELLA
	//Numero Colonne Tabella
	$tablesquery = $db->query("PRAGMA table_info(" . $tabella . ");");
	$numColonne = 0;
	while($column = $tablesquery->fetchArray(SQLITE3_ASSOC)){
		$numColonne = $numColonne+1;
	};
	echo "<TABLE border = '1' width='100%'>";
	//Nome tabella
	echo "<TR><TD colspan='" . $numColonne . "' align='center'><B>" . $tabella . "</B></TD></TR><TR>";
	//Prima riga con i nomi delle colonne
	while ($nomeColonna = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
		echo '<TD><B>' . $nomeColonna['name'] . '</B></TD>';
	}
	echo "</TR>";
	//Il resto delle righe con il contenuto della tabella
	$ret = $db->query("SELECT * FROM " . $tabella . ";");
	while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
		echo "<TR>";
		while ($nomeColonna = $tablesquery->fetchArray(SQLITE3_ASSOC)){
			echo "<TD>" . $row[$nomeColonna['name']] . "</TD>";
		}
		echo "</TR>";
	}
	echo "</TABLE>";
	//FINE TABELLA


	echo "Operazione completata con successo<BR />";
	//Chiusura connessione con il database
	$db->close();
	?>
</body>

</html>
