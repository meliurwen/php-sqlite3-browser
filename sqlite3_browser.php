<html>

<head>
	<title>SQLite3 Database Browser</title>
</head>

<body>

	<?php

	if ((isset($_GET['database']) && !empty($_GET['database'])) && (isset($_GET['tabella']) && !empty($_GET['tabella']))){

		$filename = $_GET['database'];
		$database = $filename;
		$tabella = $_GET['tabella'];


		echo "<p><a href='".$_SERVER['PHP_SELF']."?database=".$database."'>Indietro</a></p>";

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
		while($row = $ret->fetchArray(SQLITE3_ASSOC)){
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


	} elseif (isset($_GET['database']) && !empty($_GET['database'])){

		$database = $_GET["database"];

		echo "<p><a href='".$_SERVER['PHP_SELF']."'>Indietro</a></p>";
	
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
			echo "<TD><a href='".$_SERVER['PHP_SELF']."?tabella=".$table['name']."&database=".$database."'>" .$table['name'] . "</a></TD>";
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
	} else {
		echo "<h3>SQLite3 Database List</h3>";
		if ($handle = opendir('.')) {
			$i = 0;
			while (false !== ($file = readdir($handle)))
			{
		                $fileExtension = strtolower(substr($file, strrpos($file, '.') + 1));
				if ($file != "." && $file != ".." && ($fileExtension == 'sqlite3' || $fileExtension == 'sqlite' || $fileExtension == 'db'))
				{
					echo '<li><a href="'.$_SERVER['PHP_SELF'].'?database='.$file.'">'.$file.'</a> | <a href="' . $file . '">Download</a></li>';
					$i = $i + 1;
				}
			}
			closedir($handle);
			if($i == 0)
				echo "<p>Nessun database presente.</p>";
		}
	}


	?>

</body>
</html>
