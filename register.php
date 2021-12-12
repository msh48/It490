<?php
require_once 'connection.php';
session_start();
if(isset($_SESSION['user'])) {
  header("location: welcome.php");
}
if(isset($_REQUEST['register_btn'])){


  $name = filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var(strtolower($_REQUEST['email']), FILTER_SANITIZE_EMAIL);
  $password = strip_tags($_REQUEST['password']);

  if(empty($name)){
    $errorMsg[0][] = 'Name  u Required';
  }
  if(empty($email)){
    $errorMsg[1][] = 'Email Required';
  }
  if(empty($password)){
    $errorMsg[2][] = ' password required';
  }
  if(strlen($password)<6){
    $errorMsg[2][] = 'password at least 6 charecter';
  }


  if(empty($errorMsg)){
    try{
      $select_stmt = $db->prepare("SELECT name,email FROM users WHERE email = :email");
      $select_stmt->execute([':email' => $email]);
      $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

      if(isset($row['email'])== $email){
        $errorMsg[1][] ="Email exists, Please login Insted";
      }
      else{
        $hashed_passward = password_hash($password, PASSWORD_DEFAULT); //hased passward
        $created = new DateTime();
        $created = $created->format('Y-m-d H:i:s');

        $insert_stmt = $db->prepare("INSERT INTO users (name,email,password,created) VALUES (:name,:email,:password,:created)");
        if(
          $insert_stmt->execute(
            [
              ':name' =>$name,
              ':email' =>$email,
              ':password' => $hashed_passward,
              ':created' => $created,

            ]

            )
          ){
            header("location: index.php?msg=".urlencode('click the verification email'));
          }
      }


    }
    catch(PDOEexception $e){
      $pdoError = $e->getMessage();
    }
  }

}


?>



<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <form action="register.php" method="post">
      <div class="mb-3">

        <?php
           if(isset($errorMsg[0])){
             foreach($errorMsg[0] as $nameErrors) {
               echo "<p class='small text-danger'>".$nameErrors."</p>";
          }
        }
         ?>


        <label for="name" class="form-label"> Name</label>
        <input type="text" name="name" class="form-control" placeholder="Shafayet Hussain">
        </div>


      <div class="mb-3">
        <?php
        if(isset($errorMsg[1])){
          foreach($errorMsg[1] as $emailErrors) {
            echo "<p class='small text-danger'>".$emailErrors."</p>";
          }
        }



         ?>
        <label for="email" class="form-label"> Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="jame@goc.com">
        </div>
        <div class="mb-3">
          <?php
          if(isset($errorMsg[2])){
            foreach($errorMsg[2] as $passwordErrors) {
              echo "<p class='small text-danger'>".$passwordErrors."</p>";
            }
          }



           ?>
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="">
          </div>
          <button type="Submit" name="register_btn" class="btn btn-primary">Register Account</button>



    </form>
    Already have an account? <a class="register" href="register.php"> Login Insted </a>

  </div>
</body>
</html>
