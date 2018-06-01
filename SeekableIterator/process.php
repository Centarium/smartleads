<?php


for($i=0;$i<6;$i++)
{
    $pid = pcntl_fork();

    if ($pid==0) {
        pcntl_wait($status); //"Нулевые" родители
    }else{

        //echo "Fork ".$pid.'_'.microtime(true)."!\n";
        pcntl_wait($status); /* Гарантия, что процессы не превратятся в
            зомби и не начнут есть системные ресурсы. ps aux | grep process.php в помощь */

        $res = system('php test.php --seek '.random_int(100000,150000));
        echo $res;
    }
}
