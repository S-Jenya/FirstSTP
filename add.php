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
  <title>Dr. Chuck's Profile Add</title>
</head>
<body>
  
  <div class="container">
    <p><h1>Dr. Chuck's Profile Add</h1></p>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if (isset($_POST['first_name']) && isset($_POST['last_name']) &&
       isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

        //$msg = validateprofile();
        $msg = validatePos();
        $msgEdu = validateEdu();
          // Data validation
          if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
            || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
            echo "<p style=\"color:red\">All values are required</p>";

          } elseif (strpos($_POST['email'],'@') === false) {
            //$_SESSION['added'] = '-';
            echo "<p style=\"color:red\">Email address must contain @</p>";
          } elseif (is_string($msg)){
               echo "<p style=\"color:red\">";
               echo $msg;
               echo "</p>";
           } elseif (is_string($msgEdu)){
               echo "<p style=\"color:red\">";
               echo $msgEdu;
               echo "</p>";   
          } else {

            $stmt = $pdo->prepare('INSERT INTO `profile` (`profile_id`, `user_id`, `first_name`, `last_name`, `email`, `headline`, `summary`) VALUES (NULL, :user_id, :f_name, :l_name, :email, :headline, :summary);');
            $stmt->execute(array( 
              ':user_id' => $_SESSION['success'], 
              ':f_name' => $_POST['first_name'], 
              ':l_name' => $_POST['last_name'], 
              ':email' => $_POST['email'], 
              ':headline' => $_POST['headline'], 
              ':summary' => $_POST['summary'])
            );
            $profile_id = $pdo->lastInsertId();

            $rank = 1;
            for($i = 1; $i<=9; $i++){
              if( !isset($_POST['year'.$i]) ) continue;
              if( !isset($_POST['desc'.$i]) ) continue;
              $year = $_POST['year'.$i];
              $desc = $_POST['desc'.$i];
              $stmt = $pdo->prepare('INSERT INTO `position` (`position_id`, `profile_id`, `rank`, `year`, `description`) 
                VALUES (NULL, :pid, :rank, :year, :descc)');
              $stmt->execute(array( 
                ':pid' => $profile_id, 
                ':rank' => $rank, 
                ':year' => $year, 
                ':descc' => $desc)
              );

              $rank++;
            }

            // блок добавление нового места учёбы
            $rank = 1;
            for($i = 1; $i<=9; $i++){
              if( !isset($_POST['edu_school'.$i]) ) continue;
              $year = $_POST['edu_year'.$i];
              $inst_name = $_POST['edu_school'.$i];
              $stmt = $pdo->prepare('SELECT * FROM `institution` WHERE name = :inst_name');
              $stmt->execute(array( ':inst_name' =>  $inst_name));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);

              $institution_id;
              if($row !== false) $institution_id = $row['institution_id'];
              if ($row === false) {
                $stmt = $pdo->prepare('INSERT INTO `institution` (`name`) VALUES (:inst_name)');
                $stmt->execute(array( ':inst_name' =>  $inst_name));
                $institution_id = $pdo->lastInsertId();
              }
               $stmt = $pdo->prepare('INSERT INTO `education` (`profile_id`, `institution_id`, `rank`, `year`) 
                VALUES (:pid, :inst_id, :rank, :year)');
               $stmt->execute(array( 
                  ':pid' => $profile_id, 
                  ':inst_id' => $institution_id,
                  ':rank' => $rank, 
                  ':year' => $year)
                );
                $rank++;
            }

            $_SESSION['added'] = '+';
            header('Location: index.php');
          } 
        } 

      ?>

    <form method="POST">  
      <p>First Name: <input type="text" name="first_name" size="60"></p>
      <p>Last Name: <input type="text" name="last_name" size="60"> </p>
      <p>Email: <input type="text" name="email" size="30"> </p>
      <p>Headline:</p> <input type="text" name="headline" size="80">  
      <p>Summary: </p><textarea name="summary" rows="8" cols="80"></textarea>
      <p>Education: <input type="submit" id="addEdu" value="+"></p>
      <p>Position: <input type="submit" id="addPos" value="+">      </p> 

      <div id="edu_fields"></div>
      <div id="position_fields"></div>
      <a href="school.php">json</a>
     
      <p>
        <input type="submit" value="Add">
        <input type="submit" onclick="javascript:history.back(); return false;" value="Cancel"> 
      </p> 
     
    </form>  
   
  

  <script>
    
  countPos = 0;
  countEdu = 0;

  // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
  $(document).ready(function(){
      console.log('Document ready called');
      $('#addPos').click(function(event){
        console.log('Document ready click(function(event');
          event.preventDefault();
          if ( countPos >= 9 ) {
              alert("Maximum of nine position entries exceeded");
              return;
          }
          countPos++;
          window.console && console.log("Adding position "+countPos);
          $('#position_fields').append(
              '<div id="position'+countPos+'"> \
              <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
              <input type="button" value="-" \
                  onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
              <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
              </div>');
      });

       $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });

  });



  </script>
  </div>

</body>
</html>
