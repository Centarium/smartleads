<?php
include_once __DIR__.'/model/FeedBack.php';

use BDProvider\FeedBack;

$type = $argv[1];
$model = new FeedBack();

if( $type == '--migrateUp' )
{
    $model->migrateUp();
}
elseif($type == '--migrateDown')
{
    $model->migrateDown();
}