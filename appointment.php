<?php
include("doctorNav.php");
include('db.php');
echo '<div class="row about">
        <div class="col-lg-3 col-md-4 col-sm-12">
        <form method="post" action="" id="form12345" style="padding-left: 7%; top: 0%; width: 1wh;">
                <h4>Информация о пациенте</h4>
                <div class="input-group rounded">
    <input type="search" class="form-control rounded" id="searchInput" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
    <span class="input-group-text border-0" id="search-addon">
        <button type="button" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="" viewBox="0 0 18 18">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
        </button>
    </span>
    </form>
</div>';
// Проверяем, было ли отправлено значение 'idProgram22' из предыдущей формы
$thisPatient = $_GET['idApp2'] ?? null;
if ($_GET['idApp3'] != null){
$thisPatient = $_GET['idApp3'] ?? null;
}

if(isset($_POST['idProgram22'])) {
    // Получаем значение 'idProgram22'
    $thisPatient = mysqli_real_escape_string($db, $_POST['idProgram22']);
    $thisAppointment = mysqli_real_escape_string($db, $_POST['idProgram11']);
     }
    // Выполняем запрос к базе данных
     if ($thisPatient != null){
    $sql = "SELECT * from patient WHERE ID_Patient = '$thisPatient'";
    $result = mysqli_query($db, $sql);
    
    if($result) {
        // Получаем данные пациента
        $myrow = mysqli_fetch_array($result);
        
        // Выводим информацию о пациенте

        echo '<form method="post" action="updatePatient.php" id="form12346" style="padding-left: 8%; top: 0%; width: 1wh;">
        <input type="hidden" name="idApp3" value="'.$thisPatient.'">
                <p>Фамилия: ' . $myrow['Surname'] . '</p>
                <p>Имя: ' . $myrow['Name'] . '</p>
                <p>Отчество: ' . $myrow['SecondName'] . '</p>
                <p>Дата рождения: ' . $myrow['BirthDate'] . '</p>
                <!-- Другие данные о пациенте здесь -->
                <div class="form-group">
                    <label for="allergies">Аллергии:</label>
                    <textarea type="text" id="allergies" name="allergies" class="form-control" rows="5">' . $myrow['Allergy'] . '</textarea>
                </div>
                <div class="form-group">
                
                    <label for="chronic">Хронические заболевания:</label>
                   <textarea id="chronic" name="chronic" class="form-control" rows="5">'. $myrow['Chronic'].'</textarea>

                </div>
                <br>
                <button type="submit" name="edit123" id="edit123" class="btn btn-success">Изменить</button>
            </form>';
        
echo '</div>';
        } else {
        echo "Ошибка выполнения запроса:" . mysqli_error($db);
    }
        echo '<div class="col-lg-9 col-md-8 col-sm-12 desc" style="padding-left: 3%; padding-right: 5%; top: 0%; width: 1wh;">
        <form method="post" action="" id="form12347" style="display: flex; align-items: center; justify-content: space-between; ">
        <h3>История: </h3>
        <input type="hidden" name="idApp" value="'.$thisAppointment.'">
        <input type="hidden" name="idApp2" value="'.$thisPatient.'">
        <button type="submitNew" class="btn btn-success" formaction="doctor.php">Новая запись</button>

        </form>
        ';
        $sql1 = "SELECT 
            appointment.*, 
            patient.Surname AS PatientSurname, 
            patient.Name AS PatientName, 
            patient.SecondName AS PatientSecondName,
            doctor.Surname AS DoctorSurname,
            doctor.Name AS DoctorName,
            doctor.SecondName AS DoctorSecondName, 
            doctor.Ocupation AS Ocupation
        FROM 
            appointment 
        INNER JOIN 
            patient ON appointment.ID_Patient = patient.ID_Patient 
        INNER JOIN 
            doctor ON appointment.ID_Doctor = doctor.ID_Doctor 
        WHERE 
            appointment.ID_Patient = '$thisPatient' AND (appointment.Status='1' OR appointment.Status='2')";
        $result1 = mysqli_query($db, $sql1);
        //$myrow1 = mysqli_fetch_array($result1); // Ошибка исправлена здесь
        while ($myrow1 = mysqli_fetch_array($result1)) {
        echo '<div class="groupHistory">
        <div class"NameDate" style="display: flex; align-items: center; justify-content: space-between; ">
        <h5>'.$myrow1['DoctorSurname'].' '.$myrow1['DoctorName'].' '.$myrow1['DoctorSecondName'].' '.$myrow1['Ocupation'].'</h5>
        <p>'.$myrow1['DateA'].' '.$myrow1['TimeA'] .'</p>
        </div>
        <p>Лечение: '.$myrow1['Treatment'].'</p>
        <p>Рекомендации: '.$myrow1['Prescription'].'</p>
        
        </div>';
    }
        echo '</div>
      </div>';
      
    
} else {

    echo "Пациент не выбран.";
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Bootstrap Bundle JS (Cloudflare CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>
