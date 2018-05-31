<?php
include __DIR__.'/TextReader.php';

use FileReader\TextReader;

$mem = new Memcache();



$type = $argv[1];
$index = $argv[2];

$_SESSION['count'] = 2;

if( $type == '--seek' && isset($index) )
{
    $reader = new TextReader('text.txt');
    $reader->seek($index);
    echo $reader->current();
}