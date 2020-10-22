<!DOCTYPE html>
<html lang="en">
<?php if(isset($_COOKIE["login"]) == FALSE): ?>
<head>
  <meta charset="UTF-8">
 <script
  src="https://code.jquery.com/jquery-2.2.4.js"
  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
  crossorigin="anonymous"></script>
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
<link rel="stylesheet" type="text/css" href="style.css" >
  <title>Main page</title>
</head>
<body>

  <div class="container">
    <p><h1>Главная страница</h1></p>

    <?php
      require_once 'connection.php';
      session_start();
      if(!isset($_SESSION['success'])){
        echo "<p><a href=\"login.php\">Please log in</a></p>";
        echo "<br>";

        $stmt = $pdo->query("SELECT profile_id, Concat(first_name ,' ',  last_name) as name, headline FROM `profile`"); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<table border="1">'."\n";
         echo "<th>Name</th>";
         echo "<th>Headline</th>";
        foreach ( $rows as $row ) {
            echo "<tr><td><a href=\"view.php?profile_id=".$row['profile_id']."\">";
            echo($row['name']);
            echo("</td><td>");
            echo($row['headline']);
            echo("</td></tr>\n");
        }
        echo "</table>\n";

       
        
      }  else {
        if(isset($_SESSION['added']) && $_SESSION['added'] === '+'){
          echo "<p style=\"color: green;\">Profile added</p>";
          unset($_SESSION['added']);
        } else if(isset($_SESSION['success_upd']) && $_SESSION['success_upd'] === 'Profile updated'){
          echo "<p style=\"color: green;\">Profile updated</p>";
          unset($_SESSION['success_upd']);
        } else if(isset($_SESSION['success_del']) && $_SESSION['success_del'] === 'Profile deleted'){
          echo "<p style=\"color: green;\">Profile deleted</p>";
          unset($_SESSION['success_del']);
        } else if(isset($_SESSION['success_institution_del']) && $_SESSION['success_institution_del'] === 'Institution delete'){
          echo "<p style=\"color: green;\">Учебное учреждение успешно удалено</p>";
          unset($_SESSION['success_institution_del']);
        } else if(isset($_SESSION['error_institution_del']) && $_SESSION['error_institution_del'] === 'Error institution delete'){
          echo "<p style=\"color: red;\">Удаление не возможно. Найдена пораждённая запись!</p>";
          unset($_SESSION['error_institution_del']);
        } else if(isset($_SESSION['success_institution_add']) && $_SESSION['success_institution_add'] === 'Institution add'){
          echo "<p style=\"color: green;\">Учебное учреждение успешно добавлено</p>";
          unset($_SESSION['success_institution_add']);
        } else if(isset($_SESSION['success_institution_upd']) && $_SESSION['success_institution_upd'] === 'Institution upd'){
          echo "<p style=\"color: green;\">Данные об учреждении успешно обновлены</p>";
          unset($_SESSION['success_institution_upd']);
        } else if(isset($_SESSION['success_user_add']) && $_SESSION['success_user_add'] === 'User add'){
          echo "<p style=\"color: green;\">Пользователь успешно добавлен в User</p>";
          unset($_SESSION['success_user_add']);
        } else if(isset($_SESSION['success_user_upd']) && $_SESSION['success_user_upd'] === 'User upd'){
          echo "<p style=\"color: green;\">Данные пользователя (User) успешно обновлены</p>";
          unset($_SESSION['success_user_upd']);
        } else if(isset($_SESSION['error_user_del']) && $_SESSION['error_user_del'] === 'Error user delete'){
          echo "<p style=\"color: red;\">Ошибка удаления. У пользователя (User) найдены картачки в личном профиле</p>";
          unset($_SESSION['error_user_del']);
        } else if(isset($_SESSION['success_user_del']) && $_SESSION['success_user_del'] === 'User deleted'){
          echo "<p style=\"color: green;\">Пользователь (User) успешно удалён</p>";
          unset($_SESSION['success_user_del']);
        }

        echo "<p><a href=\"logout.php\">logout</a></p>";
        echo "<br>";

        echo "<p><h1>Учётные записи</h1></p>";
         $stmt = $pdo->query("SELECT user_id, name, email FROM `users`"); 
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          echo '<table border="1">'."\n";
           echo "<th>id</th>";
           echo "<th>name</th>";
           echo "<th>email</th>";
          foreach ( $rows as $row ) {
              echo "<tr>";
              echo("</td><td>");
              echo($row['user_id']);
              echo("</td><td>");
              echo($row['name']);
              echo("</td><td>");
              echo($row['email']);
              echo("</td><td>");
              echo('<a href="edit_user.php?user_id='.$row['user_id'].'">Edit</a> ');
              echo('<a href="delete_user.php?user_id='.$row['user_id'].'">Delete</a>');
              echo("</td>");
              echo "<tr>\n";
          }
          echo "</table>\n";
          echo "<p><a href=\"add_user.php\">Добавить нового пользователя</a></p>";
          echo "<br>";

          echo "<p><h1>Пользователи системы</h1></p>";
          $stmt = $pdo->query("SELECT profile_id, Concat(first_name ,' ',  last_name) as name, headline FROM `profile`
             WHERE user_id =" .$_SESSION['success']. ";"); 
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          echo '<table border="1">'."\n";
           echo "<th>Name</th>";
           echo "<th>Headline</th>";
           echo "<th>Actions</th>";
          foreach ( $rows as $row ) {

              echo "<tr><td><a href=\"view.php?profile_id=".$row['profile_id']."\">";
              echo($row['name']);
              echo("</a>");

              echo("</td><td>");
              echo($row['headline']);

              echo("</td><td>");
              echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> ');
              echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
              echo("</td></tr>\n");
          }
          echo "</table>\n";
          echo "<p><a href=\"add.php\">Добавить новую запись</a></p>";
          echo "<br>";

        

          echo "<p><h1>Institutions list</h1></p>";
         $stmt = $pdo->query("SELECT * FROM `institution`"); 
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          echo '<table border="1">'."\n";
           echo "<th>Institutions name</th>";
          foreach ( $rows as $row ) {
              echo "<tr>";
              echo("</td><td>");
              echo($row['name']);
              echo("</td><td>");
              echo('<a href="edid_institut.php?institution_id='.$row['institution_id'].'">Edit</a> ');
              echo('<a href="delete_institution.php?institution_id='.$row['institution_id'].' name="btn_inst_del"">Delete</a>');
              echo("</td>");
              echo "<tr>\n";
          }
          echo "</table>\n";
          echo "<p><a href=\"add_institut.php\">Добавить новое учреждение</a></p>";

      }
      
    ?>
  </div>
  
<?php else: ?>
  <script>
      document.location.replace("auth.php");
  </script>
<?php endif; ?>
<script>
 $('#p').hide();
</script>

<script>
   $(document).ready(function(){
      console.log('Document ready called');
      $('#btn_inst_del').click(function(event){
        console.log('btn_inst_del click');
         
      });

  });
</script>
</body>
</html>