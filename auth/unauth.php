<?php
require_once "../src/Auth.php";
Auth::unAuth();
header("Location: ..");
exit;
?>