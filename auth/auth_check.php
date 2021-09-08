<?php
session_name("AfterCoffeeID");
session_start();
if (!isset($_SESSION["postData"])) {
    $_SESSION["postData"] = [
        "title" => $_POST["pageName"],
        "text" 	=> $_POST["textbox"],
    ];
}
if (!isset($_SESSION["authorized"]) || $_SESSION["authorized"] == 0) {
    header("Location: ../auth/?r=" . $_SERVER['REQUEST_URI']);
    exit;
}
