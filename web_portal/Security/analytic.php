<?php 
	$session_email = $ssession_type = "";
	// Resume the session 
	session_start();
	// If $_SESSION['email'] not set, force redirect to login page 
	if (!isset($_SESSION['email']) && !isset($_SESSION['type'])) { 
		header("Location: ../login.php");
	} else { // Otherwise, assign the values into $session_email & $ssession_type
		$session_email = $_SESSION['email'];
		$session_type = $_SESSION['type'];
		if($session_type != "Security") {
			header("Location: ../login.php");
		}
	}
?> 

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "author" content = "Sabrina Tan">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Analytics</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Hairline&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">

    <style>
        .tab-con{
          box-shadow: 0 2px 16px rgba(0,0,0,.1);
          border-radius: 5px;
          width: fit-content;
          margin-bottom: 0px;
        }

        .tabs {
          display: flex;
          list-style: none;
          padding: 10px;
          margin-top: 15px;
          
        }
        .tabs li {
          padding: 10px;
          color: #919191;
          background: none;
          border: none;
          cursor: pointer;
          font-weight: bold;
        }

        .tabs li.active,
        .tabs li:hover {
          color: rgba(0, 100, 0, 1);
        }

        .content {
          background-color: #fff;
        }

        .content > div {
          padding: 20px;
        }

        .content > div:not(:first-child) {
          display: none;
        }

    </style>
</head>

<body>
	<!--Sidebar starts here-->
  <div class="navigation_bar">
  <div class="logo_container"> 
  <img src="../images/naim.png" class="naim_logo"></img>
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
  <div class="logo_tail"><span>ANPR</span></div> 
  </div>
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="index.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="analytic.php"><i class="fa fa-line-chart"></i>Analytics</a></div>
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="report.php"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php" class="active_page"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
        <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div> 

  <?php 
  
  if($session_type == "Super Admin") {
      echo '<div class="navigation_links drop_down_btn"><a href="#"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left:0px; padding-left:8px;"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
        <div class="navigation_links"><a href="manage_user.php"></i>View User</a></div>
    </div>';
  }
  ?>  

  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>

  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<div class="content-container">
    <header>
		<h1>Analytics</h1>
	</header>

    <section>
        <form method="post" action="" class="date_selector">
            <label class="date_selector_label">Start Date</label> <input type="date" id="start" name="start" class="date_input">
            <label class="date_selector_label">End date</label> <input type="date" id="end" name="end" class="date_input">
            <button type="submit" class="button_submit" name ="submit" value="Submit">Search</button>
        </form>

        <div class="tab-con">
        <ul class="tabs">
            <li class="active" data-cont=".one">Daily</li>
            <li data-cont=".two">Monthly</li>
            <li data-cont=".three">Yearly</li>
        </ul>
        </div>

        <div class="content">
            <div class="one"><?php include 'graph_day.php'; ?></div>
            <div class="two"><?php include 'graph_month.php'; ?></div>
            <div class="three"><?php include 'graph_year.php'; ?></div>
        </div>
        
    </section>
</div>

<script>
    let tabs = document.querySelectorAll(".tabs li");
    let tabsArray = Array.from(tabs);
    let divs = document.querySelectorAll(".content > div");
    let divsArray = Array.from(divs);

    // console.log(tabsArray);

    tabsArray.forEach((ele) => {
      ele.addEventListener("click", function (e) {
        // console.log(ele);
        tabsArray.forEach((ele) => {
          ele.classList.remove("active");
        });
        e.currentTarget.classList.add("active");
        divsArray.forEach((div) => {
          div.style.display = "none";
        });
        // console.log(e.currentTarget.dataset.cont);
        document.querySelector(e.currentTarget.dataset.cont).style.display = "block";
      });
    });
</script>


</body>
</html>