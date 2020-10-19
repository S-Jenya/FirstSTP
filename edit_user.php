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
  
	<title>Редактировать пользователя</title>
</head>
<body>
	 <div class="container">
    <h1>едактирование User</h1>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if (isset($_POST['name']) && isset($_POST['email'])) {

          if ( strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) {
              echo "<p style=\"color:red\">Все поля обязательны для заполнения</p>";
            } elseif (strpos($_POST['email'],'@') === false) {
              echo "<p style=\"color:red\">Email address must contain @</p>";
            } else {
              $stmt = $pdo->prepare('UPDATE `users` SET `name`=:new_name,`email`=:my_email,`password`=:password 
                WHERE `user_id` = :user_id;');
              $stmt->execute(array(
                  ':new_name' => $_POST['name'], 
                  ':my_email' => $_POST['email'], 
                  ':password' => MD5($_POST['password']), 
                  ':user_id' => $_GET['user_id'])
                );               

              $_SESSION['success_user_upd'] = 'User upd';
              header('Location: index.php');
          } 
        } 
      ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT * FROM `users` WHERE user_id = ' ".$_GET['user_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Имя: <input type=\"text\" name=\"name\" size=\"60\" value=\"".$row['name']."\"></p>";
            echo "<p>Email: <input type=\"text\" name=\"email\" size=\"60\" value=\"".$row['email']."\"></p>";
        ?>

      <p>Пароль: <input type="password" name="password" size="30"> </p>
       <br>

	    <input type="submit" name="btn_save" value="Изменить">
	    <input type="submit" onclick="javascript:history.back(); return false;" value="Отменить">

    </form>
  </div>
</body>
</html>



