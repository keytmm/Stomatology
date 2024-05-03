<?php
include('db.php');

// Получение данных из запроса
$allergies = $_POST['allergies'];
$chronic = $_POST['chronic'];
$idApp3 = $_POST['idApp3'];

// Обновление данных пациента
$sql = "UPDATE patient SET Allergy='$allergies', Chronic='$chronic' WHERE ID_Patient='$idApp3'";
$result = mysqli_query($db, $sql);

if ($result) {
    // Перенаправление на страницу с информацией о пациенте
    header("Location: appointment.php?idApp3=$idApp3");
    exit(); // Завершение выполнения скрипта
} else {
    echo "Ошибка: " . mysqli_error($db);
}
?>