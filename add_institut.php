<!DOCTYPE html>
<?php 
    require_once "connection.php";

      require_once "util.php";
    if (isset($_REQUEST["logout"])) {
        nulify_cookie();
    }
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="script.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
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
  <title>Add Institut</title>
</head>
<body>
  
  <div class="container">
    <p><h1>Форма добавления нового учреждения</h1></p>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if (isset($_POST['name'])) {

          if ( strlen($_POST['name']) < 1 ) {
            echo "<p style=\"color:red\">Введите название учреждения</p>";
          } else {

            $stmt = $pdo->prepare('INSERT INTO `institution`(`institution_id`, `name`) VALUES (NULL, :name);');
            $stmt->execute(array(':name' => $_POST['name']));               

            $_SESSION['success_institution_add'] = 'Institution add';
            header('Location: index.php');
          } 
        } 
      ?>

    <form method="POST">  
      <p>Наименование учреждения: <input type="text" name="name" size="60"></p>     
      <p>
        <input type="submit" value="Добавить">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Отменить"> 
      </p> 
     
    </form>  
   
  <script>
  </script>
  </div>

</body>
</html>
