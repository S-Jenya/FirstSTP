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
  <title>Deleteing Profile</title>
</head>
<body>

  <div class="container">
    <h1>Deleteing Profile</h1>

    <?php

      require_once 'connection.php';
      session_start();

      if(array_key_exists('test',$_POST)){
        $stmt = $pdo->query("DELETE FROM `profile` 
            WHERE `profile`.`profile_id` = ' ".$_GET['profile_id']."'; ");
        $stmt->execute();
        $_SESSION['success_del'] = 'Profile deleted';
        header('Location: index.php');
      }
    
    ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT first_name, last_name FROM `profile` 
            WHERE profile_id = ' ".$_GET['profile_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>First Name: ".$row['first_name']." </p>";
            echo "<p>Last Name: ".$row['last_name']." </p>";
        ?>
        <input type="submit" name="test" value="Delete">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Cancel">
    </form>
  </div>
</body>
</html>