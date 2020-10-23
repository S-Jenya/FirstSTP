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
   <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="script.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <title>Добавить карточку</title>
</head>
<body>
  
  <div class="container">
    <p><h1>Добавить карточку</h1></p>

    <?php
    
      require_once "connection.php";
      require_once "util.php";
      session_start();
      if(isset($_POST['btn_add'])){
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
      }
      

    ?>

    <form method="POST">  
      <p>Имя: <input type="text" name="first_name" size="60"></p>
      <p>Фамилия: <input type="text" name="last_name" size="60"> </p>
      <p>Email: <input type="text" name="email" size="30"> </p>
      <p>Паспортные данные:</p> <input type="text" name="headline" size="80">  
      <p>Кем и когда выдан: </p><textarea name="summary" rows="2" cols="80"></textarea>

      <p>Данные об образовании:</p>
      <div id="edu_fields"></div>
      <input type="submit" id="addEdu" value="Добавить">

      <div id="responsecontainer"></div>
     
      <p>
        <div class="btn_control">
          <input type="submit" name="btn_add" value="Сохранить">
          <input type="submit" onclick="javascript:history.back(); return false;" value="Отмена"> 
        </div>
      </p> 
    </form>  
   
  <script>
    
  countEdu = $('#edu_fields').children().length;

  function btn_del(count) {
            console.log("Фукция вызвана");
            $('#edu'+count).remove();
            countEdu--;
            console.log("После удаления = " + countEdu);
            return false;
        }
        
  $(document).ready(function(){
      console.log('Document ready called');

       
    
        $(document).ready(function(){
            $('#btn_test_ajax').click(function(){
                $.ajax({
                  url: "institution_data.php",
                  type: "GET",
                  dataType: "html", 
                  success:function(response){
                    $("#responsecontainer").html(response); 
                  }
                });
            });
       });

       $('#addEdu').click(function(event){
            event.preventDefault();
            if ( countEdu >= 9 ) {
                alert("Максимальное число учреждений 9шт.");
                return;
            }
            countEdu++;
              console.log("После добавления = ", countEdu);

            var HTMLcode =  '<div class="edu_case">\
                              <div id="edu'+countEdu+'" style="background-color: #FFF8DC; margin-top: 15px; padding: 10px;"> \
                                <p>Год окончания: <input type="text" name="edu_year'+countEdu+'" value="" /> \
                                <input type="button" value="Удалить" onclick="btn_del('+countEdu+');"><br></p>';

            $.ajax({
                url: "school.php",
                dataType: "json", 
                success:function(data){
                    data = JSON.parse(JSON.stringify(data));
                    HTMLcode += 'Учебное учреждение: <select name="edu_school'+countEdu+'">';
                    for(var i = 0; i < data.length; i++){
                        HTMLcode += '<option value="'+data[i]+'"> '+data[i]+'';
                    }
                    HTMLcode +='</select>\
                              </div></div>';
                    $('#edu_fields').append(HTMLcode);
                }
            });
        });

  });



  </script>
  </div>

</body>
</html>
