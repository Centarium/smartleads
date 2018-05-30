<?php
include __DIR__.'/TextReader.php';

use FileReader\TextReader;

$type = $argv[1];
$index = $argv[2];

if( $type == '--seek' && isset($index) )
{
    $reader = new TextReader('text.txt');
    $reader->seek($index);
    echo $reader->current();
}