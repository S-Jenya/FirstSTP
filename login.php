<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="script.js"></script>
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
  <title>Login Page</title>
</head>
<body>
  
  <div class="container">

    <h1>Please Log In</h1>

     <?php
      require_once "connection.php";
      session_start();

       if(array_key_exists('btn_login',$_POST)){
          if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
              $_SESSION['error'] = 'Missing data';
              header("Location: login.php");
              return;
          }

          if ( strpos($_POST['email'],'@') === false ) {
              $_SESSION['error'] = 'Bad data';
              header("Location: login.php");
              return;
          }
          $check = hash('md5', $_POST['pass']);
          $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :em AND password = :pw');
          $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          // print_r($row);
          if($row['user_id'] == ""){
            $_SESSION['success'] = '';
            //header( 'Location: login.php' ) ;
            //echo "Incorrect password";
            //echo "<p style=\"color:red\">Incorrect password</p>";
          } else {
            $_SESSION['success'] = $row['user_id'];
            header( 'Location: index.php' ) ;
            //echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
          }
       }
          
          
    ?>
    <form method="POST">
      <strong>Email</strong>
      <input type="text" name="email" id="email">
      <br>

      <strong>Password</strong>
      <input type="Password" name="pass" id="pass">
       <br>

      <input type="submit" name="btn_login" onclick="return doValidate();" value="Log In">

      <input type="submit"  onclick="javascript:history.back(); return false;" name="cancel" value="Cancel" >    
    </form>
  </div>


  
  <script>
    
    function doValidate() {
        console.log('Validating...');
        try {
            addr = document.getElementById('email').value;
            pw = document.getElementById('pass').value;
            console.log("Validating addr="+addr+" pw="+pw);
            if (addr == null || addr == "" || pw == null || pw == "") {
                alert("Both fields must be filled out");
                return false;
            }
            if ( addr.indexOf('@') == -1 ) {
                alert("Invalid email address");
                return false;
            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }

  </script>
</body>
</html>