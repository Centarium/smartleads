<?php
include __DIR__.'/TextReader.php';

use FileReader\TextReader;

$type = $argv[1];
$index = $argv[2];


$start = microtime(true);

if( $type == '--seek' && isset($index) )
{

    if( 0 === intval($index) )
    {
        echo "Строка для поиска должна быть целым числом";
        exit();
    }

    $reader = new TextReader('text.txt');
    $reader->seek($index);
    echo $reader->current();

}

echo "\n".'Время выполнения скрипта: '.round(microtime(true) - $start, 4)." сек.\n";