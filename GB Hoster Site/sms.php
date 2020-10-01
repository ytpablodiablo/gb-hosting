<?php
session_start();

$naslov = "Početna";
$fajl = "index";

include("includes.php");

include("./assets/header.php");
?>
<script src='https://assets.fortumo.com/fmp/fortumopay.js' type='text/javascript'></script>
<br><br>
<center><a id="fmp-button" href="#" rel="3f30f8361bda27732a80fe29b982842f"><img src="https://assets.fortumo.com/fmp/fortumopay_150x50_red.png" width="150" height="50" alt="Mobile Payments by Fortumo" border="0" /></a></center>
<br><br>
<?php
include("./assets/footer.php");
?>