<html>

<head>
	<title>SQLite3 Database Browser</title>
</head>

<body>

	<h3>SQLite3 Database List</h3>

	<?php


	if ($handle = opendir('.')) {
		$i = 0;
		while (false !== ($file = readdir($handle)))
		{
                        $fileExtension = strtolower(substr($file, strrpos($file, '.') + 1));
			if ($file != "." && $file != ".." && ($fileExtension == 'sqlite3' || $fileExtension == 'sqlite' || $fileExtension == 'db'))
			{
				echo '<li><a href="tableList.php?database='.$file.'">'.$file.'</a> | <a href="' . $file . '">Download</a></li>';
				$i = $i + 1;
			}
		}
		closedir($handle);
		if($i == 0)
			echo "<p>Nessun database presente.</p>";
	}


	?>

</body>
</html>
