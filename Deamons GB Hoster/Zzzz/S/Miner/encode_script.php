<?php
require 'vendor/autoload.php';

$packer = new Tholu\Packer\Packer( file_get_contents( 'Miner.js' ), 'Normal', true, false, true );
$packed_js = $packer->pack( );

echo $packed_js;
?>