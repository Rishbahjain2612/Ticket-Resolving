<?php
session_start();
?>

<?php
  $errorMsg = "";
  $usersXML = simplexml_load_file("xmlFiles/users.xml");
  if(isset($_SESSION['userid']) && isset($_SESSION['user'])){
    header('Location: main.php');
  }
//Check the login credentials with the credentials that are present in the users.xml file
  if (isset($_POST['login'])) {
    foreach ($usersXML as $user) {
      //echo md5($_POST['password']);
      if($_POST['username'] == $user->userName && md5($_POST['password']) == $user->authentication->encryptedPW) {
        $_SESSION['user'] = (string) $user->userName;
        $_SESSION['userid'] = (string) $user->userID;
        header('Location: main.php');
      }
      else{
         $errorMsg= "Wrong Credentials";
      }
    }    
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  

  
  <style>
    #login-wrapper{
    height: 100vh;
    width: 100vw;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form{
    position: relative;
    top: 50px;
    width: 100%;
    max-width: 380px;
    padding: 80px 40px 40px;
    background:rgba(0, 0, 0, 0.7);
    border-radius: 10px;
    color: #fff;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
}

.form::before{
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    background: rgba(255, 255, 255, 0.08);

    border-radius: 10px;
    pointer-events: none;
}

.form img{
    position: absolute;
    top: -50px;
    left:calc(50% - 50px);
    width: 100px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.form h2{
    text-align: center;
    letter-spacing: 1px;
    margin-bottom: 2rem;
    color: #ff652f;
}

.form .input-grp input{
    width: 100%;
    padding:10px 0;
    font-size: 1rem;
    letter-spacing: 1px;
    margin-bottom: 30px;
    border: none;
    border-bottom: 1px solid #fff;
    outline: none;
    background-color: transparent;
    color: inherit;
}

.form .input-grp label{
    position: absolute;
    left: 35px;
    padding: 15px 0px;
    font-size: 1rem;
    pointer-events: none;
    transition: .3s ease-out;
    transform:translateY(-20px);
    color:#ff652f;
    font-size: .8rem;
    
}

#submit-btn{
    display: block;
    margin-left: auto;
    border: none;
    outline: none;
    background: #ff652f;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding:10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

  </style>

  <link rel="stylesheet" href="style.css">
</head>

<body background="photo_2023-01-20_16-49-24.jpg">
  
<!-- <div class="wrapper-fadeInDown">
<h2 >MS Customer Service Portal</h2>
Tabs Titles
  <div id="formContent">
    

    Icon
    <div class="fadeIn first">
     <img src="http://danielzawadzki.com/codepen/01/icon.svg" id="icon" alt="User Icon" />
    </div>
  <form action="" method="POST">
    <div>
      <input type="text" id="login" class="fadeIn second" name="username" placeholder="login"><br>
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="password"><br>
      <input type="submit" name="login" class="fadeIn fourth" value="Login"><span><?=$errorMsg?></span>
    </div>
  </form>
</div>
</div> -->

<div id="login-wrapper" class="fadeIn first">
        <form class="form" action="" method="POST">
            <img src="images/login.png" alt="">
            <h2>Login</h2>
            <div class="input-grp">
            <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
                <label for="loginUser">User Name</label>
            </div>
            <div class="input-grp">
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                <label for="loginPassword">Password</label>
            </div>
            <input type="submit" name="login" class="fadeIn fourth" value="Login" id="submit-btn"><span><?=$errorMsg?></span>
        </form>
    </div>
</body>

</html>