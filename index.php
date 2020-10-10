<!DOCTYPE html>
<html lang="en">
<?php if(isset($_COOKIE["login"]) == FALSE): ?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
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
  <title>c80003e2</title>
</head>
<body>

  <div class="container">
    <p><h1>Chuck Severance's Resume Registry</h1></p>
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
        }
        else if(isset($_SESSION['success_upd']) && $_SESSION['success_upd'] === 'Profile updated'){
          echo "<p style=\"color: green;\">Profile updated</p>";
          unset($_SESSION['success_upd']);
        }
        else if(isset($_SESSION['success_del']) && $_SESSION['success_del'] === 'Profile deleted'){
          echo "<p style=\"color: green;\">Profile deleted</p>";
          unset($_SESSION['success_del']);
        }
        echo "<p><a href=\"logout.php\">logout</a></p>";
        echo "<br>";
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
        echo "<a href=\"add.php\">Add New Entry</a>";
        echo "<br>";
      }
      
    ?>

    <p> <strong>Note:</strong> Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data periodically - which you should not do in your implementation.</p>
    <p id="p">two
      <input type="text" name="one" value="three" title="four">
      <input type="text" name="five" class="p" value="p">
    </p>
  </div>
  
<?php else: ?>
  <script>
      document.location.replace("auth.php");
  </script>
<?php endif; ?>
<script>
 $('#p').hide();
 
</script>
</body>
</html>