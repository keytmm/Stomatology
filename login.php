<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300" type="text/css">
    <title>Stomatology</title>
</head>
<body>
<form action="#" method="POST">
<label>Вход в систему</label>

<input id="login" placeholder="Логин" type="text" name="login">
<input id="pass" placeholder="Пароль" type="password" name="password">
<button type="submit" name="submit">Войти</button>
</form>
<div id="hint">Click on the tabs</div>
<?php
if(isset($_POST['submit']))
{
    $login = $_POST['login'];
    $passw = $_POST['password'];
    if(empty($login) || empty($passw)){
        exit("Вы ввели не всю информацию");
    } else {
        include("db.php");
        $login = mysqli_real_escape_string($db, $login);
        $query = "SELECT * FROM doctor WHERE Login='$login'";
        $result = mysqli_query($db, $query);
        $myrow = mysqli_fetch_array($result);
        if(empty($myrow['Login']))
        {
            exit("Извините, пользователь с таким логином/email не зарегистрирован");
        }
        else{
            if($myrow['Password'] == $passw)
            {
                $_SESSION['Login'] = $myrow['Login'];
                $_SESSION['ID_Doctor'] = $myrow['ID_Doctor'];
                $_SESSION['Code'] = $myrow['Code'];
                if($_SESSION['Code']=="0"){
                echo "<script> document.location.href = 'admin.php'</script>"; //переход в личный кабинет менеджера
            }
            else{
                echo "<script> document.location.href = 'schedule.php'</script>"; //переход в личный кабинет студента
            }
            }
            else{
                exit("Пароль неверный");
            }
        }
    }
}
?>
</body>
</html>
