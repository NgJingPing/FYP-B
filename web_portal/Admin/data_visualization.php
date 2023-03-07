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
        if($session_type != "Admin" && $session_type != "Super Admin") {
			header("Location: ../login.php");
		}
	}
?>  

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "author" content = "Ng Jing Ping">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Data Visualiztion</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJSywl7ZreYvvtUso7fKevpsI+pYVVwnu82YO0q3V6eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Hairline&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">
    <style>
        .select-box select{
            width: 240px;
            border-radius: 4px;
            box-shadow: 0px 3px 13px rgba(0, 0, 0, 0.3);
            font-size: 1.25rem;
            padding: 7px 12px 9px 12px;
            border: none;
            background-color: #061C17;
            color: #C5E5CC;
            cursor: pointer;
        }
        
        .select-box select:focus{
            outline:none;
        }

        .dataChart{
            margin: 25px;
            padding: 15px;
            border-radius: 20px;
            border: solid 3px rgba(0, 100, 0, 1);
            background: white;
            height: auto;
        }

        .button_submit.selected {
            background-color: #4DAC62;
            color: white;
        }

        .button_save {
            font-size: 1.25rem;
            padding: 5px;
            margin-top: 30px;
            width: 5%;
            border: none;
            display: inline-block;
            height: 40px;
            border-radius: 4px;
            box-shadow: 0px 3px 13px rgba(0, 0, 0, 0.3);
            background-color: #f50f0f;
            color: #ffffff;
        }

        #myChart{
            width: 100%;
            height: auto;
            background-color: white;
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
        <div class="navigation_links"><a href="report.php" class="active_page"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php" ></i>Entry Log</a></div>
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
<!--Sidebar ends here-->
<div class="content-container">
    <header>
		<h1>Data Visualiztion</h1>
	</header>

    <div>
        <button type="button" id="pdf" class="button_save" onclick="saveChart(this)" value="pdf"><i class="fa-regular fa-file-pdf"></i></button>
        <button type="button" id="jpg" class="button_save" onclick="saveChart(this)" value="jpg"><i class="fa-solid fa-file-image"></i></button>
        <button type="button" class="button_submit selected" onclick="updateChart(this)" value="day">Daily</button>
        <button type="button" class="button_submit" onclick="updateChart(this)" value="week">Weekly</button>
        <button type="button" class="button_submit" onclick="updateChart(this)" value="month">Monthly</button>
        <button type="button" class="button_submit" onclick="updateChart(this)" value="year">Yearly</button>
        <span class="select-box">
            <select onchange="updateChart(this)">
                <option value="entry">Entry Log</option>
                <option value="exit">Exit Log</option>
                <option value="denied">Denied Access Log</option>
                <option value="total">Total Access Log</option>
            </select>
        </span>        
    </div>

    <!--Line chart starts here-->
    <div class="dataChart" id="dataChart">
        <canvas id="myChart"></canvas>
    </div>
    <!--Line chart ends here-->

    <?php
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}
    
    $myquery = "SELECT entrylog.referenceID, vehicle.vehicleID, vehicle.tenantLotNumber, vehicle.licensePlate, entrylog.entryTime, tenant.name FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID INNER JOIN tenant ON vehicle.tenantLotNumber = tenant.tenantLotNumber;";
	$result = $conn->query($myquery);

    // Convert the entrylog result set to JSON
    $entry_dates = array();
    $entry_amounts = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $entry_dates[] = $row['entryTime'];
            $entry_amounts[] = 1;
        }
    }

    $myquery2 = "SELECT exitlog.referenceID, vehicle.vehicleID, vehicle.tenantLotNumber, vehicle.licensePlate, exitlog.exitTime, tenant.name FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID INNER JOIN tenant ON vehicle.tenantLotNumber = tenant.tenantLotNumber;";
	$result2 = $conn->query($myquery2);
    
    // Convert the exitlog result set to JSON
    $exit_dates = array();
    $exit_amounts = array();
    if ($result2->num_rows > 0) {
        while($row = $result2->fetch_assoc()) {
            $exit_dates[] = $row['exitTime'];
            $exit_amounts[] = 1;
        }
    }

    $myquery3 = "SELECT deniedAccess.referenceID, vehicle.vehicleID, vehicle.tenantLotNumber, vehicle.licensePlate, deniedAccess.deniedTime, tenant.name FROM deniedAccess INNER JOIN vehicle ON deniedAccess.licensePlate = vehicle.licensePlate INNER JOIN tenant ON vehicle.tenantLotNumber = tenant.tenantLotNumber";
    $result3 = $conn->query($myquery3);
    
    // Convert the deniedlog result set to JSON
    $denied_dates = array();
    $denied_amounts = array();
    if ($result3->num_rows > 0) {
        while($row = $result3->fetch_assoc()) {
            $denied_dates[] = $row['deniedTime'];
            $denied_amounts[] = 1;
        }
    }

    $myquery4 = "SELECT entrylog.referenceID, vehicle.vehicleID, vehicle.tenantLotNumber, vehicle.licensePlate, entrylog.entryTime, tenant.name FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID INNER JOIN tenant ON vehicle.tenantLotNumber = tenant.tenantLotNumber;";
	$result4 = $conn->query($myquery4);

    // Convert the totallog result set to JSON
    $total_dates = array();
    $total_amounts = array();
    if ($result4->num_rows > 0) {
        while($row = $result4->fetch_assoc()) {
            $total_dates[] = $row['entryTime'];
            $total_amounts[] = 1;
        }
    }

    $myquery5 = "SELECT exitlog.referenceID, vehicle.vehicleID, vehicle.tenantLotNumber, vehicle.licensePlate, exitlog.exitTime, tenant.name FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID INNER JOIN tenant ON vehicle.tenantLotNumber = tenant.tenantLotNumber;";
	$result5 = $conn->query($myquery5);

    if ($result5->num_rows > 0) {
        while($row = $result5->fetch_assoc()) {
            $total_dates[] = $row['exitTime'];
            $total_amounts[] = 1;
        }
    }

    // Close the database connection
    $conn->close();
    ?>
    
    <!-- Transfer log datas from database using php and prepare the data for chart.js using javascript. -->
    <?php
        // Merge duplicate datetimes
        $merged_entry_datetime = array_unique($entry_dates);

        // Add values for duplicate datetimes
        $merged_entry_amounts = array();

        foreach ($merged_entry_datetime as $dt) {
            $keys = array_keys($entry_dates, $dt);
            $sum = 0;

            foreach ($keys as $key) {
                $sum += $entry_amounts[$key];
            }

            $merged_entry_amounts[] = $sum;
        }

        // Sort the labels array
        sort($merged_entry_datetime);
        
        // Format data into Chart.js-friendly array
        $data = array(
            'labels' => $merged_entry_datetime,
            'datasets' => array(
                array(
                    'label' => 'Entry Log',
                    'data' => $merged_entry_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data = json_encode($data);
    ?>

    <?php
        // Merge duplicate datetimes and group by week
        $merged_entry_datetime = array();
        $merged_entry_amounts = array();

        for ($i = 0; $i < count($entry_dates); $i++) {
            $week_start = strtotime('last sunday', strtotime($entry_dates[$i]));
            $week_end = strtotime('next saturday', strtotime($entry_dates[$i]));
            $week_label = date('Y-m-d', $week_start);

            if (!in_array($week_label, $merged_entry_datetime)) {
                // Add new week label
                $merged_entry_datetime[] = $week_label;
                $merged_entry_amounts[] = $entry_amounts[$i];
            } else {
                // Add to existing week value
                $index = array_search($week_label, $merged_entry_datetime);
                $merged_entry_amounts[$index] += $entry_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_entry_datetime);
        
        // Format data into Chart.js-friendly array
        $data2 = array(
            'labels' => $merged_entry_datetime,
            'datasets' => array(
                array(
                    'label' => 'Entry Log',
                    'data' => $merged_entry_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data2 = json_encode($data2);
    ?>

    <?php
        // Merge duplicate datetimes and group by month
        $merged_entry_datetime = array();
        $merged_entry_amounts = array();

        for ($i = 0; $i < count($entry_dates); $i++) {
           $month_label = date('Y-m', strtotime($entry_dates[$i]));

           if (!in_array($month_label, $merged_entry_datetime)) {
               // Add new month label
               $merged_entry_datetime[] = $month_label;
               $merged_entry_amounts[] = $entry_amounts[$i];
           } else {
               // Add to existing month value
               $index = array_search($month_label, $merged_entry_datetime);
               $merged_entry_amounts[$index] += $entry_amounts[$i];
           }
        }

        // Sort the labels array
        sort($merged_entry_datetime);
        
        // Format data into Chart.js-friendly array
        $data3 = array(
            'labels' => $merged_entry_datetime,
            'datasets' => array(
                array(
                    'label' => 'Entry Log',
                    'data' => $merged_entry_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data3 = json_encode($data3);
    ?>

    <?php
        // Merge duplicate datetimes and group by year
        $merged_entry_datetime = array();
        $merged_entry_amounts = array();

        for ($i = 0; $i < count($entry_dates); $i++) {
            $year_label = date('Y', strtotime($entry_dates[$i]));

            if (!in_array($year_label, $merged_entry_datetime)) {
                // Add new year label
                $merged_entry_datetime[] = $year_label;
                $merged_entry_amounts[] = $entry_amounts[$i];
            } else {
                // Add to existing year value
                $index = array_search($year_label, $merged_entry_datetime);
                $merged_entry_amounts[$index] += $entry_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_entry_datetime);
        
        // Format data into Chart.js-friendly array
        $data4 = array(
            'labels' => $merged_entry_datetime,
            'datasets' => array(
                array(
                    'label' => 'Entry Log',
                    'data' => $merged_entry_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data4 = json_encode($data4);
    ?>

    <?php
        // Merge duplicate datetimes
        $merged_exit_datetime = array_unique($exit_dates);

        // Add values for duplicate datetimes
        $merged_exit_amounts = array();

        foreach ($merged_exit_datetime as $dt) {
            $keys = array_keys($exit_dates, $dt);
            $sum = 0;

            foreach ($keys as $key) {
                $sum += $exit_amounts[$key];
            }

            $merged_exit_amounts[] = $sum;
        }

        // Sort the labels array
        sort($merged_exit_datetime);
        
        // Format data into Chart.js-friendly array
        $data5 = array(
            'labels' => $merged_exit_datetime,
            'datasets' => array(
                array(
                    'label' => 'Exit Log',
                    'data' => $merged_exit_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data5 = json_encode($data5);
    ?>

    <?php
        // Merge duplicate datetimes and group by week
        $merged_exit_datetime = array();
        $merged_exit_amounts = array();

        for ($i = 0; $i < count($exit_dates); $i++) {
            $week_start = strtotime('last sunday', strtotime($exit_dates[$i]));
            $week_end = strtotime('next saturday', strtotime($exit_dates[$i]));
            $week_label = date('Y-m-d', $week_start);

            if (!in_array($week_label, $merged_exit_datetime)) {
                // Add new week label
                $merged_exit_datetime[] = $week_label;
                $merged_exit_amounts[] = $exit_amounts[$i];
            } else {
                // Add to existing week value
                $index = array_search($week_label, $merged_exit_datetime);
                $merged_exit_amounts[$index] += $exit_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_exit_datetime);
        
        // Format data into Chart.js-friendly array
        $data6 = array(
            'labels' => $merged_exit_datetime,
            'datasets' => array(
                array(
                    'label' => 'Exit Log',
                    'data' => $merged_exit_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data6 = json_encode($data6);
    ?>

    <?php
        // Merge duplicate datetimes and group by month
        $merged_exit_datetime = array();
        $merged_exit_amounts = array();

        for ($i = 0; $i < count($exit_dates); $i++) {
           $month_label = date('Y-m', strtotime($exit_dates[$i]));

           if (!in_array($month_label, $merged_exit_datetime)) {
               // Add new month label
               $merged_exit_datetime[] = $month_label;
               $merged_exit_amounts[] = $exit_amounts[$i];
           } else {
               // Add to existing month value
               $index = array_search($month_label, $merged_exit_datetime);
               $merged_exit_amounts[$index] += $exit_amounts[$i];
           }
        }

        // Sort the labels array
        sort($merged_exit_datetime);
        
        // Format data into Chart.js-friendly array
        $data7 = array(
            'labels' => $merged_exit_datetime,
            'datasets' => array(
                array(
                    'label' => 'Exit Log',
                    'data' => $merged_exit_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data7 = json_encode($data7);
    ?>

    <?php
        // Merge duplicate datetimes and group by year
        $merged_exit_datetime = array();
        $merged_exit_amounts = array();

        for ($i = 0; $i < count($exit_dates); $i++) {
            $year_label = date('Y', strtotime($exit_dates[$i]));

            if (!in_array($year_label, $merged_exit_datetime)) {
                // Add new year label
                $merged_exit_datetime[] = $year_label;
                $merged_exit_amounts[] = $exit_amounts[$i];
            } else {
                // Add to existing year value
                $index = array_search($year_label, $merged_exit_datetime);
                $merged_exit_amounts[$index] += $exit_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_exit_datetime);
        
        // Format data into Chart.js-friendly array
        $data8 = array(
            'labels' => $merged_exit_datetime,
            'datasets' => array(
                array(
                    'label' => 'Exit Log',
                    'data' => $merged_exit_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data8 = json_encode($data8);
    ?>
    
    <?php
        // Merge duplicate datetimes
        $merged_denied_datetime = array_unique($denied_dates);

        // Add values for duplicate datetimes
        $merged_denied_amounts = array();

        foreach ($merged_denied_datetime as $dt) {
            $keys = array_keys($denied_dates, $dt);
            $sum = 0;

            foreach ($keys as $key) {
                $sum += $denied_amounts[$key];
            }

            $merged_denied_amounts[] = $sum;
        }

        // Sort the labels array
        sort($merged_denied_datetime);
        
        // Format data into Chart.js-friendly array
        $data9 = array(
            'labels' => $merged_denied_datetime,
            'datasets' => array(
                array(
                    'label' => 'Denied Access Log',
                    'data' => $merged_denied_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data9 = json_encode($data9);
    ?>

    <?php
        // Merge duplicate datetimes and group by week
        $merged_denied_datetime = array();
        $merged_denied_amounts = array();

        for ($i = 0; $i < count($denied_dates); $i++) {
            $week_start = strtotime('last sunday', strtotime($denied_dates[$i]));
            $week_end = strtotime('next saturday', strtotime($denied_dates[$i]));
            $week_label = date('Y-m-d', $week_start);

            if (!in_array($week_label, $merged_denied_datetime)) {
                // Add new week label
                $merged_denied_datetime[] = $week_label;
                $merged_denied_amounts[] = $denied_amounts[$i];
            } else {
                // Add to existing week value
                $index = array_search($week_label, $merged_denied_datetime);
                $merged_denied_amounts[$index] += $denied_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_denied_datetime);
        
        // Format data into Chart.js-friendly array
        $data10 = array(
            'labels' => $merged_denied_datetime,
            'datasets' => array(
                array(
                    'label' => 'Denied Access Log',
                    'data' => $merged_denied_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data10 = json_encode($data10);
    ?>

    <?php
        // Merge duplicate datetimes and group by month
        $merged_denied_datetime = array();
        $merged_denied_amounts = array();

        for ($i = 0; $i < count($denied_dates); $i++) {
           $month_label = date('Y-m', strtotime($denied_dates[$i]));

           if (!in_array($month_label, $merged_denied_datetime)) {
               // Add new month label
               $merged_denied_datetime[] = $month_label;
               $merged_denied_amounts[] = $denied_amounts[$i];
           } else {
               // Add to existing month value
               $index = array_search($month_label, $merged_denied_datetime);
               $merged_denied_amounts[$index] += $denied_amounts[$i];
           }
        }

        // Sort the labels array
        sort($merged_denied_datetime);
        
        // Format data into Chart.js-friendly array
        $data11 = array(
            'labels' => $merged_denied_datetime,
            'datasets' => array(
                array(
                    'label' => 'Denied Access Log',
                    'data' => $merged_denied_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data11 = json_encode($data11);
    ?>

    <?php
        // Merge duplicate datetimes and group by year
        $merged_denied_datetime = array();
        $merged_denied_amounts = array();

        for ($i = 0; $i < count($denied_dates); $i++) {
            $year_label = date('Y', strtotime($denied_dates[$i]));

            if (!in_array($year_label, $merged_denied_datetime)) {
                // Add new year label
                $merged_denied_datetime[] = $year_label;
                $merged_denied_amounts[] = $denied_amounts[$i];
            } else {
                // Add to existing year value
                $index = array_search($year_label, $merged_denied_datetime);
                $merged_denied_amounts[$index] += $denied_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_denied_datetime);
        
        // Format data into Chart.js-friendly array
        $data12 = array(
            'labels' => $merged_denied_datetime,
            'datasets' => array(
                array(
                    'label' => 'Denied Access Log',
                    'data' => $merged_denied_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data12 = json_encode($data12);
    ?>

    <?php
        // Merge duplicate datetimes
        $merged_total_datetime = array_unique($total_dates);

        // Add values for duplicate datetimes
        $merged_total_amounts = array();

        foreach ($merged_total_datetime as $dt) {
            $keys = array_keys($total_dates, $dt);
            $sum = 0;

            foreach ($keys as $key) {
                $sum += $total_amounts[$key];
            }

            $merged_total_amounts[] = $sum;
        }

        // Sort the labels array
        sort($merged_total_datetime);
        
        // Format data into Chart.js-friendly array
        $data13 = array(
            'labels' => $merged_total_datetime,
            'datasets' => array(
                array(
                    'label' => 'Total Access Log',
                    'data' => $merged_total_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data13 = json_encode($data13);
    ?>

    <?php
        // Merge duplicate datetimes and group by week
        $merged_total_datetime = array();
        $merged_total_amounts = array();

        for ($i = 0; $i < count($total_dates); $i++) {
            $week_start = strtotime('last sunday', strtotime($total_dates[$i]));
            $week_end = strtotime('next saturday', strtotime($total_dates[$i]));
            $week_label = date('Y-m-d', $week_start);

            if (!in_array($week_label, $merged_total_datetime)) {
                // Add new week label
                $merged_total_datetime[] = $week_label;
                $merged_total_amounts[] = $total_amounts[$i];
            } else {
                // Add to existing week value
                $index = array_search($week_label, $merged_total_datetime);
                $merged_total_amounts[$index] += $total_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_total_datetime);
        
        // Format data into Chart.js-friendly array
        $data14 = array(
            'labels' => $merged_total_datetime,
            'datasets' => array(
                array(
                    'label' => 'Total Access Log',
                    'data' => $merged_total_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data14 = json_encode($data14);
    ?>

    <?php
        // Merge duplicate datetimes and group by month
        $merged_total_datetime = array();
        $merged_total_amounts = array();

        for ($i = 0; $i < count($total_dates); $i++) {
           $month_label = date('Y-m', strtotime($total_dates[$i]));

           if (!in_array($month_label, $merged_total_datetime)) {
               // Add new month label
               $merged_total_datetime[] = $month_label;
               $merged_total_amounts[] = $total_amounts[$i];
           } else {
               // Add to existing month value
               $index = array_search($month_label, $merged_total_datetime);
               $merged_total_amounts[$index] += $total_amounts[$i];
           }
        }

        // Sort the labels array
        sort($merged_total_datetime);
        
        // Format data into Chart.js-friendly array
        $data15 = array(
            'labels' => $merged_total_datetime,
            'datasets' => array(
                array(
                    'label' => 'Total Access Log',
                    'data' => $merged_total_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data15 = json_encode($data15);
    ?>

    <?php
        // Merge duplicate datetimes and group by year
        $merged_total_datetime = array();
        $merged_total_amounts = array();

        for ($i = 0; $i < count($total_dates); $i++) {
            $year_label = date('Y', strtotime($total_dates[$i]));

            if (!in_array($year_label, $merged_total_datetime)) {
                // Add new year label
                $merged_total_datetime[] = $year_label;
                $merged_total_amounts[] = $total_amounts[$i];
            } else {
                // Add to existing year value
                $index = array_search($year_label, $merged_total_datetime);
                $merged_total_amounts[$index] += $total_amounts[$i];
            }
        }

        // Sort the labels array
        sort($merged_total_datetime);
        
        // Format data into Chart.js-friendly array
        $data16 = array(
            'labels' => $merged_total_datetime,
            'datasets' => array(
                array(
                    'label' => 'Total Access Log',
                    'data' => $merged_total_amounts,
                    'fill' => false,
                    'backgroundColor' => 'rgba(50, 205, 50, 0.2)',
                    'borderColor' => 'rgba(50, 205, 50, 1)',
                    'lineTension' => 0.1
                )
            )
        );

        // Convert data to JSON format
        $json_data16 = json_encode($data16);
    ?>

    <script>
        // navigate selection button
        const buttons = document.querySelectorAll('.button_submit');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(otherButton => {
                    otherButton.classList.remove('selected');
                });
                button.classList.add('selected');
            });
        });

        // Get the data from php
        var entry_day = <?php echo $json_data;?>;
        var entry_week = <?php echo $json_data2;?>;
        var entry_month = <?php echo $json_data3;?>;
        var entry_year = <?php echo $json_data4;?>;
        var exit_day = <?php echo $json_data5;?>;
        var exit_week = <?php echo $json_data6;?>;
        var exit_month = <?php echo $json_data7;?>;
        var exit_year = <?php echo $json_data8;?>;
        var denied_day = <?php echo $json_data9;?>;
        var denied_week = <?php echo $json_data10;?>;
        var denied_month = <?php echo $json_data11;?>;
        var denied_year = <?php echo $json_data12;?>;
        var total_day = <?php echo $json_data13;?>;
        var total_week= <?php echo $json_data14;?>;
        var total_month = <?php echo $json_data15;?>;
        var total_year = <?php echo $json_data16;?>;

        var filter = "day";
        var log = "entry";

        // Create chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: entry_day,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Entry Log (Daily)',
                        font: {
                            size: 20,
                            weight: 1000
                        },
                        color:"black"
                    },
                    tooltip: {
                        enabled: true,
                        displayColors: false,
                        backgroundColor: 'White',
                        borderColor: 'rgba(50, 205, 50, 1)',
                        borderWidth: 2,
                        titleColor: 'black',
                        titleAlign: 'center',
                        titleFont:{
                            size: 18
                        },
                        bodyColor: 'black',
                        bodyAlign: 'center',
                        bodyFont:{
                            size: 16
                        },
                        cornerRadius: 2,
                        yAlign: 'bottom'

                    }
                },
                onHover: (event, chartElement) => {
                    event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                }
                
                
            }
        });

        // Update chart 
        function updateChart(period){
            //console.log(period.value);
            if(period.value == "day"){
                if (log == "entry"){
                    myChart.data = entry_day;
                    myChart.options.plugins.title.text = 'Entry Log (Daily)';
                }else if(log == 'exit'){
                    myChart.data = exit_day;
                    myChart.options.plugins.title.text = 'Exit Log (Daily)';
                }
                else if(log == 'denied'){
                    myChart.data = denied_day;
                    myChart.options.plugins.title.text = 'Denied Access Log (Daily)';
                }
                else if(log == 'total'){
                    myChart.data = total_day;
                    myChart.options.plugins.title.text = 'Total Access Log (Daily)';
                }
                filter = "day";
            }
            else if(period.value == "week"){
                if (log == "entry"){
                    myChart.data = entry_week;
                    myChart.options.plugins.title.text = 'Entry Log (Weekly)';
                }else if(log == 'exit'){
                    myChart.data = exit_week;
                    myChart.options.plugins.title.text = 'Exit Log (Weekly)';
                }
                else if(log == 'denied'){
                    myChart.data = denied_week;
                    myChart.options.plugins.title.text = 'Denied Access Log (Weekly)';
                }
                else if(log == 'total'){
                    myChart.data = total_week;
                    myChart.options.plugins.title.text = 'Total Access Log (Weekly)';
                }
                filter = "week";
            }
            else if(period.value == "month"){
                if (log == "entry"){
                    myChart.data = entry_month;
                    myChart.options.plugins.title.text = 'Entry Log (Monthly)';
                }
                else if(log == 'exit'){
                    myChart.data = exit_month;
                    myChart.options.plugins.title.text = 'Exit Log (Monthly)';
                }
                else if(log == 'denied'){
                    myChart.data = denied_month;
                    myChart.options.plugins.title.text = 'Denied Access Log (Monthly)';
                }
                else if(log == 'total'){
                    myChart.data = total_month;
                    myChart.options.plugins.title.text = 'Total Access Log (Monthly)';
                }
                filter = "month";
            }
            else if(period.value == "year"){
                if (log == "entry"){
                    myChart.data = entry_year;
                    myChart.options.plugins.title.text = 'Entry Log (Yearly)';
                }
                else if(log == 'exit'){
                    myChart.data = exit_year;
                    myChart.options.plugins.title.text = 'Exit Log (Yearly)';
                }
                else if(log == 'denied'){
                    myChart.data = denied_year;
                    myChart.options.plugins.title.text = 'Denied Access Log (Yearly)';
                }
                else if(log == 'total'){
                    myChart.data = total_year;
                    myChart.options.plugins.title.text = 'Total Access Log (Yearly)';
                }
                filter = "year";
            }
            else if(period.value == "entry"){
                if (filter == "day"){
                    myChart.data = entry_day;
                    myChart.options.plugins.title.text = 'Entry Log (Daily)';
                }
                else if (filter == "week"){
                    myChart.data = entry_week;
                    myChart.options.plugins.title.text = 'Entry Log (Weekly)';
                }
                else if (filter == "month"){
                    myChart.data = entry_month;
                    myChart.options.plugins.title.text = 'Entry Log (Monthly)';
                }
                else if (filter == "year"){
                    myChart.data = entry_year;
                    myChart.options.plugins.title.text = 'Entry Log (Yearly)';
                }
                log="entry"
            }
            else if(period.value == "exit"){
                if (filter == "day"){
                    myChart.data = exit_day;
                    myChart.options.plugins.title.text = 'Exit Log (Daily)';
                }
                else if (filter == "week"){
                    myChart.data = exit_week;
                    myChart.options.plugins.title.text = 'Exit Log (Weekly)';
                }
                else if (filter == "month"){
                    myChart.data = exit_month;
                    myChart.options.plugins.title.text = 'Exit Log (Monthly)';
                }
                else if (filter == "year"){
                    myChart.data = exit_year;
                    myChart.options.plugins.title.text = 'Exit Log (Yearly)';
                }
                log="exit"
            }
            else if(period.value == "denied"){
                if (filter == "day"){
                    myChart.data = denied_day;
                    myChart.options.plugins.title.text = 'Denied Access Log (Daily)';
                }
                else if (filter == "week"){
                    myChart.data = denied_week;
                    myChart.options.plugins.title.text = 'Denied Access Log (Weekly)';
                }
                else if (filter == "month"){
                    myChart.data = denied_month;
                    myChart.options.plugins.title.text = 'Denied Access Log (Monthly)';
                }
                else if (filter == "year"){
                    myChart.data = denied_year;
                    myChart.options.plugins.title.text = 'Denied Access Log (Yearly)';
                }
                log="denied"
            }
            else if(period.value == "total"){
                if (filter == "day"){
                    myChart.data = total_day;
                    myChart.options.plugins.title.text = 'Total Access Log (Daily)';
                }
                else if (filter == "week"){
                    myChart.data = total_week;
                    myChart.options.plugins.title.text = 'Total Access Log (Weekly)';
                }
                else if (filter == "month"){
                    myChart.data = total_month;
                    myChart.options.plugins.title.text = 'Total Access Log (Monthly)';
                }
                else if (filter == "year"){
                    myChart.data = total_year;
                    myChart.options.plugins.title.text = 'Total Access Log (Yearly)';
                }
                log="total"
            }
            myChart.update();
        }

        // Save chart
        function saveChart(period){
            //console.log(period.value);
            if(period.value == "pdf"){
                // create a new PDF document
                var pdf = new jsPDF('landscape');

                // Get the canvas element as an image
                var canvas = document.getElementById('myChart');
                var canvasImage = canvas.toDataURL('image/jpg', 1.0);

                // Add the image to the PDF document
                pdf.setFontSize(20);
                pdf.addImage(canvasImage, 'JPG', 10, 10, 280, 180);

                // Delay for 500 milliseconds
                setTimeout(function() {
                    // Save the PDF document
                    pdf.save('data_visualization.pdf');
                }, 500);
            }
            if(period.value == "jpg"){
                // Get the canvas element as an image
                var canvas = document.getElementById('myChart');
            
                // Delay for 500 milliseconds
                setTimeout(function() {
                    // Save the JPG document
                    html2canvas(canvas, {
                        backgroundColor: 'white'
                    }).then(function(canvas) {
                        var link = document.createElement('a');
                        link.href = canvas.toDataURL('image/jpeg');
                        link.download = 'data_visualization.jpg';
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }, 500);


            }
        }

    </script>
    
    
</div>
</body>
</html>