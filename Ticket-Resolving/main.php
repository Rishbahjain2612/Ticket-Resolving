<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
  $usersXML = simplexml_load_file("xmlFiles/users.xml");
  $tickets = [];
  if(!isset($_SESSION['userid']) || !isset($_SESSION['user'])){
    header('Location: login.php');
  }

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
        if (isset($_POST['updateStatus'])) {
          foreach ($ticketsXML as $ticket) {
            if($_POST['ticketID'] == $ticket->ticketID) {
              $ticket->tcktStatus = $_POST['tcktStatus'];
              $ticketsXML->saveXML("xmlFiles/tickets.xml");  
              break;
            }
          }
        }
      //   if($admin && isset($_POST['search'])){
      //     $search_term = $_POST['search_term'];
      //     $filtered_tickets = [];

      //     foreach ($tickets as $ticket) {
      //       if (strpos(strtolower($ticket->tcktName),strtolower( $search_term)) !== false) {
      //         $filtered_tickets[] = $ticket;
      //       }
      //     }
      //     var_dump($search_term);
      //     var_dump($filtered_tickets);
      // }
    }
  } 
  
 
  }

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
  <title>Tickets | <?= $_SESSION['user']; ?></title>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link rel="stylesheet" href="style.css">
  <style>
    h1{
      text-align: center;
      margin-top:50px;
      color:white;
    }
    .search-results-box {
  width: 50%;
  padding: 20px;
  margin: 20px auto;
  background-color: #f2f2f2;
  border-radius: 5px;
}

  </style>
</head>

<body background="photo_2023-01-20_16-49-24.jpg">
  <div class="container">
  <h1>All the available tickets</h1>

  <?php if($admin) { ?>
    <form action="" method="post">
        <label for="search_term">Search Term:</label>
        <input type="text" id="search_term" name="search_term">
        <input type="submit" value="Search" name="search" class="btn">
    </form>
    <div class="search-results-box">
  <?php
    if($admin && isset($_POST['search'])){
        $search_term = $_POST['search_term'];
        $filtered_tickets = [];
        foreach ($tickets as $ticket) {
          if (strpos(strtolower($ticket->tcktName), strtolower($search_term)) !== false) {
            $filtered_tickets[] = $ticket;
          }
        }
        if(!empty($filtered_tickets)){
            echo "<h3>Search Results:</h3><br>";
            foreach($filtered_tickets as $ticket){
              // echo "<a href='ticket-page.php?ticketID=".$ticket->ticketID."'>".$ticket->ticketName."</a>";
                echo "<li> <a href='eachticket.php?ticketID=".$ticket->ticketID."'>".$ticket->tcktName."</a></li>";
            }
        }else{
            echo "<p>No results found for '".$search_term."'</p>";
        }
    }
  ?>
</div>
<?php } ?>
<?php if($admin == false) { ?>
  <a href="NewTicket.php" class="btn btn-primary">Raise a Ticket</a><br><br>
  <?php } ?>
  <a href="logout.php" class="btn btn-primary">Logout</a><br>
  <div class="tickets-container">
    <?php
      foreach($tickets as $ticket) {
    ?>
    <a href="eachticket.php?ticketID=<?= $ticket->ticketID; ?>">
    <div class="ticket ticket-<?= $ticket->tcktStatus; ?>">
        <h2><?= $ticket->tcktName; ?></h2>
        <?php if($admin) { ?> <p>User: <?= getUsername($ticket->userID); ?></p> <?php } ?>
        <p>Date: <?= $ticket->date; ?></p>
        <p class="badge"><?= $ticket->priority; ?> Priority</p>
        
        <?php if($admin) { ?> 
            
          <form action="" method="POST">
            <input type="hidden" name="ticketID" value="<?= $ticket->ticketID; ?>">
            <select name="tcktStatus" onclick="return false;">
              <option value="Resolved" <?= $ticket->tcktStatus == "Resolved" ? "selected" :"" ?>>Resolved</option>
              <option value="To-DO" <?= $ticket->tcktStatus == "To-DO" ? "selected" :"" ?>>To-DO</option>
              <option value="On-going" <?= $ticket->tcktStatus == "On-going" ? "selected" :"" ?>>On-going</option>
              <option value="Re-Open" <?= $ticket->tcktStatus == "Re-Open" ? "selected" :"" ?>>Re-Open</option>
            </select>
            <input type="submit" value="Update Status" name="updateStatus" onclick="this.parent.submit();">
          </form>
        <?php } else { ?>
          <p class="badge"><?= $ticket->tcktStatus; ?></p>
        <?php } ?>
        <p><?= $ticket->tcktDesc; ?></p>
    </div>
    </a>
    <?php
      }
    ?>
  </div>
  </div>

</body>

</html>