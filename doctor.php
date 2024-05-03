<?php
include("doctorNav.php");
include('db.php');
$idProgram = $_POST['idApp'];
$idProgram2 = $_POST['idApp2'];
?>
<div class="row about">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form method="post" action="" id="#form" style="left: 5%; top: 0%;width: 1wh; padding-left: 30%;padding-right: 30%;">
                <input type="hidden" name="idProgram" value="<?php echo $idProgram; ?>">
                <input type="hidden" name="idProgram2" value="<?php echo $idProgram2; ?>">
                <h4>Прием</h4>
                <label>Лечение:</label>
                <textarea type="text" name="edit1" value="" class="form-control" rows="5"></textarea>
                <br>
                <label>Рекомендации:</label>
                <textarea type="text" name="edit2" value="" class="form-control" rows="5"></textarea>
                <br>
                <label>Стоимость:</label>
                <input type="number" name="edit3" class="form-control">
                <br>

                <button type="submit" name="editSubmit" class="btn btn-success" value="<?php echo $idProgram2; ?>">Сохранить</button>
                <a href="appointment.php?idApp2=<?php echo $idProgram2; ?>" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
<?php

if(isset($_POST['editSubmit'])){
    $idApp = $_POST['idProgram'];
    $idApp2 = $_POST['idProgram2'];
    $Treatment = $_POST['edit1'];
    $Prescription = $_POST['edit2'];
    $price = $_POST['edit3'];
    
	$sql="UPDATE appointment SET Treatment='$Treatment', Prescription='$Prescription', Cost='$price', Status='1' WHERE ID_Appointment='$idApp'";
	$result=mysqli_query($db,$sql);
	if($result==TRUE)
	{
		echo "Данные успешно изменены!";
		echo "<script> document.location.href = 'schedule.php'</script>";
	}
	else{
		echo "Ошибка" . mysqli_error($db);
	}
}
?>
</body>
</html>