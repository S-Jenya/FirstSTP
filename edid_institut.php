<!DOCTYPE html>
<html lang="en">
<head>
	<style>
    body{
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container{
      width: 60%;
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }
  </style>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  
	<title>Изменить учебное учреждение</title>
</head>
<body>
	 <div class="container">
    <h1>Изменить название учебного учредения</h1>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if (isset($_POST['name'])) {

          if ( strlen($_POST['name']) < 1 ) {
            echo "<p style=\"color:red\">Введите название учреждения</p>";
          } else {

            $stmt = $pdo->prepare('UPDATE `institution` SET `name` = :new_name WHERE `institution_id` = :inst_id');
            $stmt->execute(array(
                ':new_name' => $_POST['name'], 
                ':inst_id' => $_GET['institution_id'])
              );               

            $_SESSION['success_institution_upd'] = 'Institution upd';
            header('Location: index.php');
          } 
        } 
      ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT name FROM `institution` WHERE institution_id = ' ".$_GET['institution_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Наименование учреждения: <input type=\"text\" name=\"name\" size=\"60\" value=\"".$row['name']."\"></p>";
        ?>
       <br>

	    <input type="submit" name="btn_save" value="Изменить">
	    <input type="submit" onclick="javascript:history.back(); return false;" value="Отменить">

    </form>
  </div>
</body>
</html>



