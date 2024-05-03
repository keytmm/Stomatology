<?php
include("adminNav.php");
include('db.php');
// Функция для получения названия дня недели на русском языке
function getDayOfWeek($date) {
    $daysOfWeek = array(
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
        7 => 'Воскресенье'
    );
    $dayNumber = date('N', strtotime($date));
    return $daysOfWeek[$dayNumber];
}

// Установка начального значения selectedDate на сегодняшнюю дату
$selectedDate = date('Y-m-d');

// Получаем список дат (понедельник - пятница)
$dates = array();
$startDate = new DateTime();
$endDate = new DateTime('+3 week');
while ($startDate <= $endDate) {
    if ($startDate->format('N') >= 1 && $startDate->format('N') <= 5) {
        $dates[] = array(
            'date' => $startDate->format('Y-m-d'),
            'dayOfWeek' => getDayOfWeek($startDate->format('Y-m-d'))
        );
    }
    $startDate->modify('+1 day');
    if ($startDate->format('N') === '6') {
        $startDate->modify('+2 day'); // Пропускаем субботу и воскресенье
    }
}

// Установка выбранной даты на первую дату в списке
$selectedDate = $dates[0]['date'];
?>

<script>
    function selectDate(date) {
        document.getElementById("selectedDate").value = date;
        document.getElementById("dateForm").submit(); // Добавлено: отправка формы после выбора даты
    }
</script>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3>Выберите дату:</h3>
            <div class="btn-group-vertical">
                <?php foreach ($dates as $date): ?>
                    <button class="btn custom-btn date-button <?php echo ($date['date'] == $selectedDate) ? 'selected' : ''; ?>" data-date="<?php echo $date['date']; ?>" onclick="selectDate('<?php echo $date['date']; ?>')"><?php echo $date['date'] . ' (' . $date['dayOfWeek'] . ')'; ?></button>
                <?php endforeach; ?>
            </div>
            <form id="dateForm" method="post" style="display: none;"> <!-- Добавлено: форма для отправки выбранной даты -->
                <input type="hidden" id="selectedDate" name="selectedDate" value="<?php echo $selectedDate; ?>">
            </form>
        </div>

        <div class="col-md-9">
            <?php
            if (!empty($_POST['selectedDate'])) {
                $thisdate = mysqli_real_escape_string($db, $_POST['selectedDate']);
                $sql = "SELECT 
            appointment.*, 
            patient.Surname AS PatientSurname, 
            patient.Name AS PatientName, 
            patient.SecondName AS PatientSecondName,
            patient.Passport_Series,
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
            appointment.DateA = '$thisdate' AND appointment.Status='0'";

                $result = mysqli_query($db, $sql);
                if (!$result) {
                    die("Ошибка выполнения запроса: " . mysqli_error($db));
                }
                echo "<h4> Расписание на " . $thisdate . "</h4>"; // Перенесено сюда
                echo "<table class='table table-bordered table-sm'>
                        <tr class='table-primary'><th>ФИО пациента</th><th>ФИО врача</th><th>Время</th><th>Дата</th><th>Данные</th><th></th>";
                while ($myrow = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $myrow['PatientSurname'] ." " .$myrow['PatientName'] ." ". $myrow['PatientSecondName'] . "</td>";
                    echo "<td>" . $myrow['DoctorSurname'] ." ". $myrow['DoctorName'] ." ". $myrow['DoctorSecondName'] . "</td>";
                    echo "<td>" . $myrow['TimeA'] . "</td>";
                    echo "<td>" . $myrow['DateA'] . "</td>";
                    if ($myrow['Passport_Series']== NULL){
                    	echo "<td>Данные не заполнены</td>";
                    	echo "<td><form method='post' action=''>
      <button type='submit' class='btn btn-success fill-data-button' >Заполнить</button>
      <input type='hidden' name='idP' value='" . $myrow['ID_Patient'] . "'>
      </form></td>";
                    }
                    else{
                    	echo "<td>Данные заполнены</td>";
                    
                    echo "<td></td>";
                      }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Не выбрана дата";
            }
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="fillDataModal" tabindex="-1" role="dialog" aria-labelledby="fillDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fillDataModalLabel">Заполнить данные</h5>
                
            </div>
            <div class="modal-body">
                <form id="fillDataForm" method="post" action="">
                	<input type="hidden" id="idPatient" name="idPatient" class="form-control" value="">
                    <div class="form-group">
                        <label for="patientName">ФИО пациента:</label>
                        <input type="text" id="patientName" name="patientName" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="data1">Серия паспорта:</label>
                        <input type="text" id="data1" name="data1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="data2">Номер паспорта:</label>
                        <input type="text" id="data2" name="data2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="data3">Полис:</label>
                        <input type="text" id="data3" name="data3" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="submit" form="fillDataForm" name="submit" class="btn btn-success">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Bootstrap Bundle JS (Cloudflare CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
    // Обработчик нажатия на кнопку "Заполнить"
    $(document).on('click', '.fill-data-button', function(event) {
        // Предотвращаем стандартное действие кнопки внутри формы
        event.preventDefault();
        var patientId = $(this).closest('tr').find('input[name="idP"]').val();

            // Получаем ФИО пациента из текущей строки
            var patientFullName = $(this).closest('tr').find('td:first').text().trim();

            // Устанавливаем значение ID пациента в скрытом поле модального окна
            $('#idPatient').val(patientId);
        
        // Устанавливаем значение ФИО пациента в поле модального окна
        $('#patientName').val(patientFullName);
        
        // Отображаем модальное окно
        $('#fillDataModal').modal('show');
        
        // Выводим сообщение в консоль
        console.log("Button clicked");
    });
});
</script>
<?php
if(isset($_POST['submit'])){
    $idP = $_POST['idPatient'];
    $PS = $_POST['data1'];
    $PN = $_POST['data2'];
    $Polis = $_POST['data3'];
    
	$sql="UPDATE patient SET Passport_Series='$PS', Passport_Number='$PN', Health_Insurance_Policy='$Polis' WHERE ID_Patient='$idP'";
	$result=mysqli_query($db,$sql);
	if($result==TRUE)
	{
		echo "Данные успешно изменены!";
		echo "<script> document.location.href = 'admin2.php'</script>";
	}
	else{
		echo "Ошибка";
	}
}
?>
</body>
</html>