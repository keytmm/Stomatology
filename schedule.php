<?php
include("doctorNav.php");
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
                    <button class="btn custom-btn date-button <?php echo ($date['date'] === $selectedDate) ? 'selected' : ''; ?>" data-date="<?php echo $date['date']; ?>" onclick="selectDate('<?php echo $date['date']; ?>')"><?php echo $date['date'] . ' (' . $date['dayOfWeek'] . ')'; ?></button>
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
                $sql = "SELECT appointment.*, patient.ID_Patient, patient.Surname AS PatientSurname, patient.Name AS PatientName, patient.SecondName AS PatientSecondName FROM appointment INNER JOIN patient ON appointment.ID_Patient = patient.ID_Patient WHERE appointment.ID_Doctor = '$idDoc' AND appointment.DateA = '$thisdate' AND appointment.Status='0'";
                $result = mysqli_query($db, $sql);
                if (!$result) {
                    die("Ошибка выполнения запроса: " . mysqli_error($db));
                }
                echo "<h4> Расписание на " . $thisdate . "</h4>"; // Перенесено сюда
                echo "<table class='table table-bordered table-sm'>
                        <tr class='tabletr'><th>ФИО пациента</th><th>Время</th><th>Дата</th><th></th>";
                while ($myrow = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $myrow['PatientSurname'] ." ". $myrow['PatientName'] ." ". $myrow['PatientSecondName'] . "</td>";
                    echo "<td>" . $myrow['TimeA'] . "</td>";
                    echo "<td>" . $myrow['DateA'] . "</td>";
                    echo "<td><form method='post' action='appointment.php'>
                            <button type='submit' class='btn btn-success'>Открыть</button>
                            <input type='hidden' name='idProgram11' value='" . $myrow['ID_Appointment'] . "'>
                            <input type='hidden' name='idProgram22' value='" . $myrow['ID_Patient'] . "'>
                          </form></td>";
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

