<?php

include(__DIR__ . '/Converter.php');
$content = file_get_contents('test.in');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="bbs.js"></script>
<link rel="stylesheet" href="bbs.css">
</head>
<body>
<div class="border">
    <pre><?= Converter::convert($content) ?></pre>
</div>
</body>
</html>
