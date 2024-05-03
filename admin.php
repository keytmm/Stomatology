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

// Получаем список дат (понедельник - пятница)
$dates = array();
$startDate = new DateTime();
$endDate = new DateTime('+11 day');
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

// Получаем список врачей из базы данных
$query = "SELECT * FROM doctor WHERE doctor.Code=1";
$result = mysqli_query($db, $query);
$doctors = [];
while ($row = mysqli_fetch_assoc($result)) {
    $doctors[] = $row;
}

function checkAppointment($db, $doctorId, $date, $time) {
    $sql = "SELECT * FROM appointment WHERE ID_Doctor = '$doctorId' AND DateA = '$date' AND TimeA = '$time'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        return "appointment-exists"; // Если запись существует, возвращаем этот класс
    } else {
        return ""; // Если запись не существует, возвращаем пустую строку
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3>Выберите врача:</h3>
            <br>
            <div class="btn-group-vertical">
                <?php foreach ($doctors as $doctor): ?>
                    <button class="btn custom-btn doctor-button" data-doctorId="<?php echo $doctor['ID_Doctor']; ?>">
        <?php echo $doctor['Surname'] ." ". $doctor['Name'] ." ". $doctor['SecondName'] ." ".$doctor['Ocupation']; ?>
    </button>
                <?php endforeach; ?>
                <input type="hidden" id="selectedDoctorId" name="selectedDoctorId" value="">
            </div>
        </div>

        <div class="col-md-9">
            <div class="schedule" id="schedule">
                <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-schedule" id="doctor_<?php echo $doctor['ID_Doctor']; ?>" style="display: none;">
                        <h3>
                            <?php echo $doctor['Surname'] ." ". $doctor['Name'] ." ". $doctor['SecondName']; ?>
                        </h3>
                        <div class="time-buttons">
                            <?php foreach ($dates as $date): ?>
                                <div class="time-button-group" data-date="<?php echo $date['date']; ?>" style="display: none;">
                                    <p><?php echo $date['date'] . ' (' . $date['dayOfWeek'] . ')'; ?></p>
                                    <?php $time = "10:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "11:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "12:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "13:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "15:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "16:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "17:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                    <?php $time = "18:00"; // Задаем время ?>
                                    <button class="btn custom-btn time-btn <?php echo checkAppointment($db, $doctor['ID_Doctor'], $date['date'], $time); ?>"><?php echo $time; ?></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const doctorButtons = document.querySelectorAll(".doctor-button");

    function showFirstDoctorSchedule() {
        const firstDoctorId = doctorButtons[0].dataset.doctorid;
        showSchedule(firstDoctorId);
    }

    function showSchedule(doctorId) {
        const doctorSchedules = document.querySelectorAll(".doctor-schedule");
        doctorSchedules.forEach(schedule => {
            if (schedule.id === 'doctor_' + doctorId) {
                schedule.style.display = "block";

                const dates = schedule.querySelectorAll(".time-button-group");
                dates.forEach(date => {
                    date.style.display = "block"; // Показываем даты
                });

                const timeButtons = schedule.querySelectorAll(".time-btn");
                timeButtons.forEach(button => {
                    button.style.display = "inline-block"; // Показываем кнопки времени
                });
            } else {
                schedule.style.display = "none";
            }
        });
    }

    showFirstDoctorSchedule();

    // Добавляем обработчики событий для кнопок выбора врача
    doctorButtons.forEach(button => {
        button.addEventListener("click", function() {
            const doctorId = this.dataset.doctorid;
            console.log("Выбран врач с ID:", doctorId);
            showSchedule(doctorId);
        });
    });
});
</script>



<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Запись на прием</h5>
            </div>
            <div class="modal-body">
<form  method="post"  action="">


<div class="input-group rounded">
    <input type="search" class="form-control rounded" name="searchInput" id="searchInput" placeholder="Поиск" aria-label="Search" aria-describedby="search-addon" />
    <span class="input-group-text border-0" id="search-addon">
        <button type="submit" name="submitSearchInput" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="" viewBox="0 0 18 18">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
    </span>
</div>
<div id="searchResults" class="mt-2"></div>

<?php
    $sur = '';
    $nam = '';
    $sec = '';
    $pho = '';
    $dat = '';
if(isset($_POST['submitSearchInput'])){
    $phone = $_POST['searchInput'];
    $sqlSearch="SELECT * FROM patient WHERE Phone=$phone";
    $resultSearch = mysqli_query($db, $sqlSearch);
    if($resultSearch==TRUE)
    {
    $patientData = mysqli_fetch_assoc($resultSearch);
    
        $sur = $patientData['Surname'];
        $nam = $patientData['Name'];
        $sec = $patientData['SecondName'];
        $pho = $patientData['Phone'];
        $dat = $patientData['BirthDate'];
       
    }
    else{
        echo "Пациент не найден";
    }
}
  echo '<div class="form-group"><label for="fio">Фамилия:</label><br><input type="text" id="fio" name="fio" class="form-control" value="'.$sur.'"></div>';
  echo '<div class="form-group"><label for="name">Имя:</label><br><input type="text" id="name" name="name" class="form-control" value="'.$nam.'"></div>';
  echo '<div class="form-group"><label for="SecondName">Отчество:</label><br><input type="text" id="SecondName" name="SecondName" class="form-control" value="'.$sec.'"></div>';

  echo '<div class="form-group"><label for="dateB">Дата рождения:</label><br><input type="date" id="dateB" name="dateB" class="form-control" value="'.$dat.'"></div>';
  echo '<div class="form-group"><label for="phone">Номер телефона:</label><br><input type="phone" id="phone" name="phone" class="form-control" value="'.$pho.'"></div>'; 
  echo '<div class="form-group dateTime"></div>'; 
  echo '<input type="hidden" id="doctorNameInput1" name="doctorName1">
  <input type="hidden" id="doctorNameInput" name="doctorName">
<input type="hidden" id="timeInput" name="time">
<input type="hidden" id="dateInput" name="date">';

?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" name="submit" class="btn btn-success" id="saveAppointment">Сохранить</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
$fio=$_POST["fio"];
$name=$_POST["name"];
$SecondName=$_POST["SecondName"];
$dateB=$_POST["dateB"]; 
$phone=$_POST["phone"];
$doctorId = $_POST['doctorName1'];
$time = $_POST['time'];
$date = $_POST['date'];

$patient = mysqli_real_escape_string($db, $phone);
        $query = "SELECT * FROM patient WHERE Phone='$phone'";
        $result = mysqli_query($db, $query);
        $myrow = mysqli_fetch_array($result);
        if(empty($myrow['Phone']))
        {
            $query="INSERT INTO patient(Surname,Name,SecondName,BirthDate,Phone) VALUES ('$fio','$name','$SecondName','$dateB','$phone')";
            $result=mysqli_query($db, $query);
        }
        $query = "SELECT * FROM patient WHERE Phone='$phone'";
        $result = mysqli_query($db, $query);
        $myrow = mysqli_fetch_array($result);
        $Id_patient=$myrow['ID_Patient'];

        $sql= "INSERT INTO appointment(ID_Patient,ID_Doctor,DateA,TimeA) VALUES ('$Id_patient','$doctorId','$date','$time')";
        $result1 = mysqli_query($db, $sql);
        if($result1==TRUE){
        echo "успешно!";
        echo "<script> document.location.href = 'admin.php'</script>";
        }
        else{
        echo "Ошибка" . mysqli_error($db);
        }
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = $('#appointmentModal');
    const timeButtons = document.querySelectorAll(".time-btn");

    timeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const time = this.textContent.trim();
            const date = this.parentElement.dataset.date;
            const doctorName = this.closest(".doctor-schedule").querySelector("h3").textContent.trim();
            const doctorId = this.closest(".doctor-schedule").id.replace("doctor_", "");

            // Устанавливаем значения в скрытые поля формы
            document.getElementById("doctorNameInput").value = doctorName;
            document.getElementById("doctorNameInput1").value = doctorId;
            document.getElementById("timeInput").value = time;
            document.getElementById("dateInput").value = date;

            // Заполняем модальное окно данными из скрытых полей
            const modalBody1 = document.querySelector("#appointmentModal .dateTime");
            modalBody1.innerHTML = `
            
                <p>Врач: ${doctorName}</p>
                <p>Время: ${time}</p>
                <p>Дата: ${date}</p>
            `;

            // Открываем модальное окно
            $('#appointmentModal').modal('show');
            <?php

    // Ваши действия при получении данных из формы поиска
    // Сохраните переданные данные о враче, времени и дате
    $doctorId = $_POST['doctorName1'];
    $doctorName = $_POST['doctorName'];
    $time = $_POST['time'];
    $date = $_POST['date'];
?>
        });
    });
    <?php if($resultSearch==TRUE) : ?> 
        // Проверяем, был ли выполнен поиск и данные успешно получены
        const doctorName = "<?php echo $doctorName ?>";
        const doctorId = "<?php echo $doctorId ?>";
        const time = "<?php echo $time ?>";
        const date = "<?php echo $date ?>";

        document.getElementById("doctorNameInput").value = doctorName;
        document.getElementById("doctorNameInput1").value = doctorId;
        document.getElementById("timeInput").value = time;
        document.getElementById("dateInput").value = date;
        // Заполняем модальное окно данными из переменных PHP
        const modalBody1 = document.querySelector("#appointmentModal .dateTime");
        modalBody1.innerHTML = `
            
            <p>Врач: ${doctorName}</p>
            <p>Время: ${time}</p>
            <p>Дата: ${date}</p>
        `;
        
        // Открываем модальное окно
        $('#appointmentModal').modal('show');
    <?php endif; ?>
});
</script>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Bootstrap Bundle JS (Cloudflare CDN) -->
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>