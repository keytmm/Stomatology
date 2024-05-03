<?php
include("adminNav.php");
include('db.php');

echo "<div class='row about'>
<div class='col-lg-3 col-md-3 col-sm-12'>
<form method='post' action='' style='padding-left: 5%; padding-right: 5%; top: 0%; width: 1wh;'>
<h4>Выберите период отчетов</h4>
<div class='input-group rounded'>
			<select name='typeSertification' value='Месяц' class='form-control'>
				<option name='opt' value='0'>Выберите месяц</option>
				<option name='opt' value='1'>Январь 2024</option>
				<option name='opt' value='2'>Февраль 2024</option>
				<option name='opt' value='3'>Март 2024</option>
				<option name='opt' value='4'>Апрель 2024</option>
				<option name='opt' value='5'>Май 2024</option>
			</select>
            <span class='input-group-text border-0' id='search-addon1'>
<button type='submit' name='submit' class='btn'><svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='currentColor' class='' viewBox='0 0 18 18'>
                <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/>
            </svg></button></span>
</div>
</div>
<div class='col-lg-9 col-md-9 col-sm-12 desc' style='padding-left: 2%; padding-right: 5%; top: 0%; width: 1wh;'>";
?>
<?php
$opt=$_POST['typeSertification'];
$sql = "SELECT CONCAT(doctor.Surname, ' ', doctor.Name, ' ', doctor.SecondName) AS FIO, 
               COUNT(appointment.ID_Appointment) AS kol, 
               SUM(appointment.Cost) AS sum 
        FROM appointment 
        INNER JOIN doctor ON appointment.ID_Doctor = doctor.ID_Doctor 
        WHERE appointment.Status = 2 AND MONTH(appointment.dateA) = '$opt' 
        GROUP BY FIO";
$result = mysqli_query($db, $sql);

if (!$result) {
    die('Ошибка выполнения запроса: ' . mysqli_error($db));
}

echo "<h4>Отчет ";
switch ($opt) {
    case '1':
        echo "Январь 2024";
        break;
    case '2':
        echo "Февраль 2024";
        break;
    case '3':
        echo "Март 2024";
        break;
    case '4':
        echo "Апрель 2024";
        break;
    case '5':
        echo "Май 2024";
        break;
    default:
        echo " не выбран";
        break;
}
echo "</h4>";
echo "<table class='table table-bordered table-sm'>
    <tr class='table-primary'><th>ФИО врача</th><th>Кол-во пациентов</th><th>На сумму</th>";

while ($myrow = mysqli_fetch_array($result)) {
    $sum += $myrow['sum'];
    $count += $myrow['kol'];
    echo "<tr>";
    echo "<td>" . $myrow['FIO'] . "</td>";
    echo "<td>" . $myrow['kol'] . "</td>";
    echo "<td>" . $myrow['sum'] . "</td>";
    echo "<tr>";
}
echo "</tr>";
echo "<td><b>Итого: </b></td><td><b>$count</b></td><td><b>$sum</b></td>";
echo "</td></tr>";
echo "</form>";
echo "</table>";
?>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>