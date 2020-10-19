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
  <title>Новый пользователь</title>
</head>
<body>
  
  <div class="container">
    <p><h1>Новый пользователь</h1></p>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if (isset($_POST['first_name']) && isset($_POST['password']) && isset($_POST['email'])) {

          if ( strlen($_POST['first_name']) < 1 || strlen($_POST['email']) < 1 || 
            strlen($_POST['password']) < 1 ) {
            echo "<p style=\"color:red\">Все поля обязательны для заполнения</p>";
          } elseif (strpos($_POST['email'],'@') === false) {
            echo "<p style=\"color:red\">Email address must contain @</p>";
          } else {

            $stmt = $pdo->prepare('INSERT INTO `users`(`user_id`, `name`, `email`,    `password`)  
                                              VALUES  (NULL,     :f_name, :my_email, :my_password);');
            $stmt->execute(array( 
              ':f_name' => $_POST['first_name'],
              ':my_email' => $_POST['email'], 
              ':my_password' => MD5($_POST['password']) )
            );
            
            $_SESSION['success_user_add'] = 'User add';
            header('Location: index.php');
          }
        } 

    ?>

    <form method="POST">  
      <p>Имя: <input type="text" name="first_name" size="30"></p>
      <p>Email: <input type="text" name="email" size="30"> </p>
      <p>Пароль: <input type="password" name="password" size="30"> </p>
     
      <p>
        <input type="submit" value="Сохранить">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Отменить"> 
      </p> 
     
    </form>  
   
  </div>

</body>
</html>
