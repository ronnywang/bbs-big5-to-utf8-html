<?php

include(__DIR__ . '/Converter.php');
$content = file_get_contents('test.in');

echo Converter::convert($content);

