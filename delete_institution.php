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
  <title>Удалить институт</title>
</head>
<body>

  <div class="container">
    <h1>Deleteing Profile</h1>

    <?php

      require_once 'connection.php';
      session_start();

      if(array_key_exists('test',$_POST)){
        $stmt = $pdo->query("SELECT count(institution_id) as count_inst FROM `education` 
            WHERE institution_id = ' ".$_GET['institution_id']."'; ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['count_inst'] > 0) {
          $_SESSION['error_institution_del'] = 'Error institution delete';
          header('Location: index.php');
        } else {
          $stmt = $pdo->query("DELETE FROM `institution` WHERE institution_id = ' ".$_GET['institution_id']."'; ");
          $stmt->execute();
          $_SESSION['success_institution_del'] = 'Institution delete';
          header('Location: index.php');
        }       
      }
    
    ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT * FROM `institution` WHERE institution_id = ' ".$_GET['institution_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Наименование учреждения: ".$row['name']." </p>";
        ?>
        <input type="submit" name="test" value="Удалить">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Отмена">
    </form>
  </div>
</body>
</html>