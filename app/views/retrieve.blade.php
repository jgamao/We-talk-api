<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>	
	<?php
		foreach ($image as $row)
		{
			$path = $row->path;
			$filename = $row->filename;
		}
	?>

	<img src="uploads/{{$filename}}", alt="image" />
</body>
</html>