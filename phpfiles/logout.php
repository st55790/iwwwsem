<?php
//unset($_SESSION['uzivatel']);
//unset($_SESSION['jmeno']);
//unset($_SESSION['prijmeni']);
//unset($_SESSION['prava']);
//unset($_SESSION['email']);
//unset($_SESSION['kosikPocet']);
session_unset();
header('Location: /');
?>