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
    <?php include "../include/head.php";?>
    <title>ANPR - Analytics</title>
</head>

<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Analytics';
    $subpage = '';
    // Give user role
    $role = "Security"; include "../include/navbar.php";
?> 
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<div class="content-container">
    <header>
		<h1>Analytics</h1>
	</header>

    <section>
        <form method="post" action="" class="date_selector">
            <label class="date_selector_label">Start Date</label> <input type="date" id="start" name="start" class="date_input" style="height: 45px;">
            <label class="date_selector_label">End date</label> <input type="date" id="end" name="end" class="date_input" style="height: 45px;">
            <button type="submit" class="button_submit" name ="submit" value="Submit" style="height: 45px;">Search</button>
        </form>

        <div class="tab-con">
        <ul class="tabs">
            <li class="active" data-cont=".one">Daily</li>
            <li data-cont=".two">Weekly</li>
            <li data-cont=".three">Monthly</li>
            <li data-cont=".four">Yearly</li>
        </ul>
        </div>

        <div class="content">
            <div class="one"><?php include 'graph_day.php'; ?></div>
            <div class="two"><?php include 'graph_week.php'; ?></div>
            <div class="three"><?php include 'graph_month.php'; ?></div>
            <div class="four"><?php include 'graph_year.php'; ?></div>
        </div>
        
    </section>
</div>

<script>
    let tabs = document.querySelectorAll(".tabs li");
    let tabsArray = Array.from(tabs);
    let divs = document.querySelectorAll(".content > div");
    let divsArray = Array.from(divs);

    // console.log(tabsArray);
    // function for switching the tab to daily, weekly, monthly or yearly
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