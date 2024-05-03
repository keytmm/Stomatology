<?php
include("adminNav.php");
include('db.php');
?>
<div class="row about">
    <div class="col-lg-3 col-md-3 col-sm-12">
        <form method="post" action="" id="form" style="padding-left: 7%; top: 0%; width: 1wh;">
        <h4>Фильтрация данных</h4>
<div class='form-check'>
<input class='form-check-input' type='radio' name='rb1' value='1' id='rb1'>
<label class='form-check-label' for='rb1'>
Оплата
</label>
</div>
<div class='form-check'>
<input class='form-check-input' type='radio' name='rb1' value='2' id='rb2'>
<label class='form-check-label' for='rb1'>
История
</label>
</div>
<br>
<button type="submit" class="btn btn-success">Показать</button>
    </form>
</div>
    <div class="col-lg-9 col-md-9 col-sm-12 desc" style="padding-right: 5%; top: 0%; width: 1wh;">
<?php
if (ISSET($_POST['submit']) or empty($j)) {
    $j = $_POST['rb1'];
    if (empty($j)) {
        $j = 1;
    }
    $kol = 20;
    $page = 1;
    $first = 0;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $first = ($page * $kol) - $kol;

    $sql = "SELECT COUNT(*) FROM appointment where appointment.Status=$j";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_row($result);
    $total = $row[0];
    $str_pag = ceil($total / $kol);
    if ($j == 2) {
        echo "<h4>История</h4>";
    } else {
        echo "<h4>Оплата</h4>";
    }
    for ($i = 1; $i <= $str_pag; $i++) {
        echo "<a href='payment.php?page=".$i."&rb1=".$j."'>Страница ".$i."</a> | ";
    }
    echo "<table class='table table-bordered table-sm'>
    <tr class='table-primary'><th>№</th><th>ФИО пациента</th><th>ФИО врача</th><th>Дата</th><th>Время</th><th>Статус</th>";
    if ($j == 1) {
        echo "<th></th>";
    }

    $sql1 = "SELECT 
            appointment.*, 
            patient.Surname AS PatientSurname, 
            patient.Name AS PatientName, 
            patient.SecondName AS PatientSecondName,
            doctor.Surname AS DoctorSurname,
            doctor.Name AS DoctorName,
            doctor.SecondName AS DoctorSecondName 
        FROM 
            appointment 
        INNER JOIN 
            patient ON appointment.ID_Patient = patient.ID_Patient 
        INNER JOIN 
            doctor ON appointment.ID_Doctor = doctor.ID_Doctor 
        WHERE 
            appointment.Status=$j ORDER BY appointment.ID_Appointment DESC LIMIT $first, $kol";
    $result1 = mysqli_query($db, $sql1);
    while ($myrow = mysqli_fetch_array($result1)) {
        echo "<tr>";
        echo "<td>" . $myrow['ID_Appointment'] . "</td>";
        echo "<td>" . $myrow['PatientSurname'] . " " . $myrow['PatientName'] . " " . $myrow['PatientSecondName'] . "</td>";
        echo "<td>" . $myrow['DoctorSurname'] . " " . $myrow['DoctorName'] . " " . $myrow['DoctorSecondName'] . "</td>";
        echo "<td>" . $myrow['DateA'] . "</td>";
        echo "<td>" . $myrow['TimeA'] . "</td>";

        if ($myrow['StatusPay'] == 2) {
            echo "<td>Картой</td>";
        } elseif ($myrow['StatusPay'] == 1) {
            echo "<td>Наличными</td>";
        } else {
            echo "<td>Не оплачено</td>";
        }
        if ($j == 1) {

            echo "<td> <form method='post'>
        <input type='hidden' name='idApp' value='" . $myrow['ID_Appointment'] . "'>";
            echo "<button type='button' name='submit' value=' ' class='btn btn-success' data-toggle='modal' data-target='#myModal' data-order='" . $myrow['ID_Appointment'] . "' data-fio='" . $myrow['PatientSurname'] . " " . $myrow['PatientName'] . " " . $myrow['PatientSecondName'] . "' data-fioDoc='" . $myrow['DoctorSurname'] . " " . $myrow['DoctorName'] . " " . $myrow['DoctorSecondName'] . "' data-name='" . $myrow['Treatment'] . "' data-cost='" . $myrow['Cost'] . "' data-dateA='" . $myrow['DateA'] . "' data-timeA='" . $myrow['TimeA'] . "'>Обработать</button>
        </td>";
        }
        echo "</form>
        </tr>";
    }
    echo "</table>";
?>

</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
  $('#myModal').on('show.bs.modal', function (event) {
// кнопка, которая вызывает модаль
 var button = $(event.relatedTarget);
// получим  data-idEdu атрибут
  var idApp = button.data('order');
// получим  data-fio атрибут
  var fio = button.data('fio');
  var fioDoc = button.data('fioDoc');
  var name = button.data('name');
  var cost = button.data('cost');
  var dateA = button.data('dateA');
  var timeA = button.data('timeA');
   // Здесь изменяем содержимое модали
  var modal = $(this);
modal.find('.modal-body #idApp').val(idApp);
 modal.find('.modal-title').text('Оплата № '+idApp);
 modal.find('.modal-body #fio').val(fio);
  modal.find('.modal-body #fioDoc').val(fioDoc);
 modal.find('.modal-body #name').val(name);
 modal.find('.modal-body #dateA').text('Date: ' + dateA);
 modal.find('.modal-body #timeA').text('Time: '+ timeA);
  modal.find('.modal-body #cost').text('Стоимость: '+cost);
})
});
</script>

<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <h4 class="modal-title">Подтверждение оплаты</h4>
      </div>
      <!-- Основное содержимое модального окна -->
       <div class="modal-body">  
         <form  method="post"  action="">
<?php
  echo '<div class="form-group"><label for="fio">Пациент:</label><br><input type="text" id="fio" name="fio" readonly class="form-control"></div>';
  echo '<div class="form-group"><label for="name">Лечение:</label><input type="text" id="name" name="name" readonly class="form-control"></div>';
  echo '<div class="form-group"><label id="cost"></label><br>';
  echo '<div class="form-check">
  <input class="form-check-input" type="radio" name="rb11" value="1" id="rb1_0">
  <label class="form-check-label" for="rb1_0">
   Оплата наличными
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="rb11" value="2" id="rb1_1">
  <label class="form-check-label" for="rb1_1">
  Оплата картой
  </label>
</div>';
//скрытое поле для хранения id заявки
echo '<br><input type="hidden" id="idApp" name="idApp">'; 
?>
</div>
<div class="modal-footer">
 <button type="button" class="btn btn-secondary close" data-dismiss="modal" 
aria-hidden="true"> Закрыть</button>
 <button type="submit" name="submit" class="btn btn-success"> Изменить статус</button>
</div>
</form>
</div>
</div>
</div>
<?php
if(isset($_POST['submit'])){
    $status=$_POST['rb11'];
    $idApp=$_POST['idApp'];
    $sql="UPDATE appointment SET StatusPay=$status, Status=2 WHERE ID_Appointment=$idApp";
    $result=mysqli_query($db,$sql);
    if($result==TRUE){
        echo "Данные успешно изменены!";
        echo "<script> document.location.href = 'payment.php'</script>";
    }
    else{
        echo "Ошибка";
    }
}
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Bootstrap Bundle JS (Cloudflare CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>