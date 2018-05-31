<?php
$file= @fopen('2.txt', 'r');
for($i=0;$i<4;$i++)
{
    fseek($file, 20*$i);
    $string = fgets($file,1024);
    var_dump($string);
}


