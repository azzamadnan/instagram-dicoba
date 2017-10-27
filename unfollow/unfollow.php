<?php

@ini_set("output_buffering", "Off");
@ini_set('implicit_flush', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('max_execution_time',1000000);
header( 'Content-type: text/html; charset=utf-8' );

include 'akun.php';
include 'API.php';
include '../index.php';

Gid();

echo "Adek Lelah... Sudah dulu bang<br/>";