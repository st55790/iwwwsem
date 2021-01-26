<?php
//require_once "../classes/Connection.php";
//$conn = Connection::getPdoInstance();
?>
<link rel="stylesheet" href="../css/login.css">
<div class="log">
    <form method="post" action="">
        <div class="form">
            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Zadej email" name="email" id="email" required><br><br>
            <label for="psw"><b>Heslo</b></label>
            <input type="password" placeholder="Zadej heslo" name="psw" id="psw" required><br><br>
            <label for="psw_repeat"><b>Heslo (pro kontrolu)</b></label>
            <input type="password" placeholder="Zadej znovu heslo" name="psw_repeat" id="psw_repeat" required><br><br>
            <label for="name"><b>Jméno</b></label>
            <input type="text" placeholder="Zadej jméno" name="name" id="name" required><br><br>
            <label for="last_name"><b>Příjmení</b></label>
            <input type="text" placeholder="Zadej příjmení" name="last_name" id="last_name" required><br><br>
            <button class="btn" name="submit" type="submit" id="register_btn">Zaregistrovat</button>
        </div>
        <div class="form">
            <p>Už máš účet ? <a href="login.php">Přihlášení</a></p>
        </div>
    </form>
</div>

<?php
//include "../classes/Database.php";
if (isset($_POST['submit'])) {
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass1 = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $pass2 = htmlspecialchars(!empty($_POST['psw_repeat']) ? trim($_POST['psw_repeat']) : null);
    $name = htmlspecialchars(!empty($_POST['name']) ? trim($_POST['name']) : null);
    $last_name = htmlspecialchars(!empty($_POST['last_name']) ? trim($_POST['last_name']) : null);
//var_dump($db->userExist($email)); //test
    if ($pass1 != $pass2){
        echo("<p class='alert'>hesla se neshodují</p>");
    }else{
        $db = new Database();
        if ($db->userExist($email)){
            echo("<p class='alert'>Uzivatel jiz existuje</p>");
        }else{
            $db->insertUser($name, $last_name, $email, $pass1, "user");
            echo("<p class='info'><b>Uzivatel zaregistrován</b></p>");
        }
    }

}

?>
