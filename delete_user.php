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
  <title>Удаление пользователя</title>
</head>
<body>

  <div class="container">
    <h1>Удаление пользователя</h1>

    <?php

      require_once 'connection.php';
      session_start();

      if(array_key_exists('test',$_POST)){
        $stmt = $pdo->query("SELECT count(user_id) as count_user  FROM `profile` 
            WHERE user_id = ' ".$_GET['user_id']."'; ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['count_user'] > 0) {
          $_SESSION['error_user_del'] = 'Error user delete';
          header('Location: index.php');
        } else {
          $stmt = $pdo->query("DELETE FROM `users` WHERE user_id = ' ".$_GET['user_id']."'; ");
          $stmt->execute();
          $_SESSION['success_user_del'] = 'User deleted';
          header('Location: index.php');
        }       
      }
    
    ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT * FROM `users` WHERE user_id = ' ".$_GET['user_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Имя: ".$row['name']." </p>";
            echo "<p>Email: ".$row['email']." </p>";
        ?>
        <input type="submit" name="test" value="Удалить">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Отмена">
    </form>
  </div>
</body>
</html>