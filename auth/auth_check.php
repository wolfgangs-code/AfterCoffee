<?php
session_name("AfterCoffeeID");
session_start();
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] == 0) {
    header('Location: auth.php');
    exit;
}
?>