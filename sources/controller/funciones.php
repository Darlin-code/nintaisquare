<?php
    require_once("pdo.php");

    //No login
    function noset() {
        if (!isset($_SESSION["USER_AUTH"])) {
            header("Location:");
            return;
        }
    }

    function set() {
        if (isset($_SESSION["USER_AUTH"])) {
            header("Location: home/");
            return;
        }
    }

    function noadmin() {
        if ($_SESSION["USER_AUTH"]["admin"] === false) {
            header("Location: ../../../");
            return;
        } else {
            if (!isset($_SESSION["USER_AUTH"]["admin_confirm"])) {
                header("Location: validation.php");
                return;
            }
        }
    }

    //Bienvenida
    function greats() {
        date_default_timezone_set("America/Santo_Domingo");
        $fecha = getdate();

        if ($fecha["hours"] === 0) {
            return "Buenas noches, ";
        } elseif ($fecha["hours"] > 18 || $fecha["hours"] < 6) {
            return "Buenas noches, ";
        } elseif ($fecha["hours"] >= 6 && $fecha["hours"] <= 12) {
            return "Buenos días, ";
        } elseif ($fecha["hours"] > 12 && $fecha["hours"] <= 18) {
            return "Buenas tardes, ";
        } else {
            return "Hola, ";
        }
    }
?>