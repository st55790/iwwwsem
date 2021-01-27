<?php
require_once "classes/Connection.php";
require_once "classes/Database.php";
include_once "classes/Product.php";

$conn = Connection::getPdoInstance();
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IWWW - Eshop</title>
    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
<?php
include 'phpfiles/header.php';
?>

<?php
//include '/phpfiles/products.php';
if($_SERVER['REQUEST_URI'] !== "/")
    require_once ("phpfiles" . strtok($_SERVER['REQUEST_URI'], '?'));
else
    require_once("phpfiles/products.php");
?>

<?php
include 'phpfiles/footer.php';
?>
</body>
</html>