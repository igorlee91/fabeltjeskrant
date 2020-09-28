<?php
session_start();
include_once "../conn/dbconn.php";
if (isset($_POST['do_plaats'])) {
    $userpost = R::dispense('userposts');
    date_default_timezone_set('Europe/Paris');
    $timestamp = date("Y-m-d");
    $userpost->voornaam = $_SESSION['voornaam'];
    $userpost->bericht = $_POST['mypost'];
    $userpost->tijdgepost = $timestamp;
    $userpost->avatar = $_SESSION['avatar'];
    $userpost->email = $_SESSION['email'];
    $userpost->idcode = $_SESSION['idcode'];
    if ($_FILES['foto']['size']>0) {
        $userpost->foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "./userimages/".$_FILES['foto']['name']);
    }
    $id = R::store($userpost);
    header("location:../userpage.php?upload=succes");
};
    
/*
echo $_SESSION['email'] . "<br>";
echo $_POST['bericht'] . "<br>";
echo $_FILES['foto']['name'];
*/