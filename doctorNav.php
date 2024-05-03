<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/Style2.css">
<title>Личный кабинет врача</title>
</head>
<body>
<nav class="navbar navbar-expand-lg brown_panel">
<a class="navbar-brand" href="#">Личный кабинет врача</a> 
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-4">
<li class="nav-item selected">
<a class="nav-link" href="schedule.php">График приема</a>
</li>
<li class="nav-item ">
<a class="nav-link" href="appointment.php">Прием</a>
</li>
<li class="nav-item">
<a class="nav-link" href="login.php">Выход</a>
</li>
</ul>
</div>
</nav>
<br>
<div>
<?php
$idDoc=$_SESSION['ID_Doctor'];
//$userName=$_SESSION['User_name'];
?>
</div>