<!--Added Nikko Zabala visit me at www.nikkozabala.me -->

<?php

$loc = '/online-dashboard/public/login';
header("Location: $loc");
header("Content-Security-Policy: default-src 'self'");
die(0);

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex">
	<title>Online Dashboard</title>
</head>
<body>
<h1>Hello World</h1>
<a href="/online-dashboard/public/">Online Dashboard</a>
</body>
</html>