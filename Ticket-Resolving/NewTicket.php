<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
$usersXML = simplexml_load_file("xmlFiles/users.xml");
$tickets = [];

// check if user is logged in
if(!isset($_SESSION['userid']) || !isset($_SESSION['user'])){
    header('Location: login.php');
}

// check if userid is set
if (isset($_SESSION['userid'])) {
    foreach ($usersXML as $user) {
        if($_SESSION['userid'] == $user->userID) {
            $admin = false;
            foreach($user->attributes() as $key => $value){
                if($value == 'admin') 
                    $admin = true;
            }
            $ticketsXML = simplexml_load_file("xmlFiles/tickets.xml");
            foreach ($ticketsXML as $ticket) {
                if(($_SESSION['userid'] == $ticket->userID || $admin)) {
                    array_push($tickets, $ticket);  
                }
            }

            // check if add ticket form is submitted
            if (isset($_POST['addTicket'])) {
                $newTicket = $ticketsXML->addChild('ticket');
                // $newTicket->addChild('ticketID', $_SESSION['ticketID']);
                $newTicket->addChild('userID', $_SESSION['userid']);
                $newTicket->addChild('date', $_POST['date']);
                $newTicket->addChild('tcktStatus', '');
                $newTicket->addChild('priority', 'Medium');
                $newTicket->addChild('messages', '');
                $newTicket->addChild('tcktName', $_POST['tcktName']);
                $newTicket->addChild('tcktDesc', $_POST['tcktDesc']);
                $ticketsXML->saveXML("xmlFiles/tickets.xml");
            }
        }
    }
} 

// function to get username from userID
function getUsername($userID)
{
    global $usersXML;
    $userName = "";
    foreach ($usersXML as $user) {
        if ((string) $userID == (string) $user->userID) {
            $userName = $user->userName;
        }
    }
    return $userName;
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>New Tickets  </title>
 <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="style.css">
  <style>
    .message{
      background-color: #b2dfdb;
    }

    h1{
      text-align: center;
      margin-top:50px;
      color:white;
    }

    .input{
        
        border: none;
        border-bottom: 3px solid #fff;
        outline: none;
        background-color: transparent;
        color: inherit;
    }
    h4{
        color:white;
    }
    
    .inputarea{
        width: 100%;
    }
  </style>
</head>

<body background="photo_2023-01-20_16-49-24.jpg">
  <div class="container">
    <h1>New Ticket Details</h1>
    <a href="logout.php" input="button" class="btn btn-primary">Logout</a>
    <a href="main.php" class="btn btn-primary">Back</a>
    <br><br><br>
      <div>
        <!-- <form action="" method="POST">
          <h4>Name: <input type="text" class="input"> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Email: <input type="text" class="input"></h4>
          <br><br>
          <h4>Description: </h4>
          <textarea class="message-box" name="message" required></textarea>
          <input type="submit" class="btn btn-primary" name="addMessage" value="Post">
        </form> -->
    <form action="" method="post">
        <label for="tcktName" >Ticket Title:</label>&nbsp &nbsp
        <input class="input" type="text" id="tcktName" name="tcktName"><br>
        <label for="tcktName">DATE:</label>&nbsp &nbsp
        <input class="input" type="date" id="date" name="date"><br><br><br>
        <h4><label for="tcktDesc">Ticket Description:</label></h4>
        <textarea id="tcktDesc" name="tcktDesc" class="inputarea"></textarea><br>
        <input type="submit" name="addTicket" value="Add Ticket" class="btn btn-primary">
    </form>
      </div>
      
    </div>
  

</body>

</html>