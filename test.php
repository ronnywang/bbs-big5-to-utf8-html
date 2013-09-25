<?php

include(__DIR__ . '/Converter.php');
$content = file_get_contents('test.in');
?>
<html>
<head>
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
