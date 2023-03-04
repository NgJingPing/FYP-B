<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Getting Started with Chart JS with www.chartjs3.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .container {
            justify-content: center;
            align-items: center;
        }
        .chartCard {
            width: 700px;
            height: auto;
            align-items: center;
            justify-content: center;
            margin: 10px; 
        }
        .chartCard p{
            font-weight: bold;
            font-size: 1.5rem;
            text-align: center;
        }
        .chartBox {
            width: 700px;
            padding: 20px;
            border-radius: 20px;
            border: solid 3px rgba(0, 100, 0, 1);
            background: white;
        }
    </style>
    
</head>

<body>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart3"></canvas>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <?php
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";
    $totalCountArray = $dateArray = $entryCountArray = $exitCountArray = $deniedCountArray = array();
    $startdate = date('Y-m-d');
    $startdate = new DateTime($startdate);
    $startdate->modify('1 month ago');
    $enddate = date('Y-m-d');
    $enddate = new DateTime($enddate);
    

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}

    if(isset($_POST["submit"])) {
        if(empty($_POST["start"])) {
            // Do nothing
        } else {
            $startdate = new DateTime($_POST["start"]);
        }

        if(empty($_POST["end"])) {
            // Do nothing
        } else {
            $enddate = new DateTime($_POST["end"]);
        }
        $enddate->modify('+1 day');
    }
    $sdate = $startdate->format("Y-m-d");
    $edate = $enddate->format("Y-m-d");
    
    while($startdate != $enddate){
        $sql = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = '$sdate') + (SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = '$sdate') AS total;";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_array($result)){
            array_push($totalCountArray, $row["total"]);
            array_push($dateArray, $sdate);
        }

        $sql2 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = '$sdate') AS total;";
        $result2 = $conn->query($sql2);
        while($row = mysqli_fetch_array($result2)){
            array_push($entryCountArray, $row["total"]);
        }

        $sql3 = "SELECT(SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = '$sdate') AS total;";
        $result3 = $conn->query($sql3);
        while($row = mysqli_fetch_array($result3)){
            array_push($exitCountArray, $row["total"]);
        }

        $sql4 = "SELECT(SELECT COUNT(*) FROM deniedaccess WHERE DATE(`deniedTime`) = '$sdate') AS total;";
        $result4 = $conn->query($sql4);
        while($row = mysqli_fetch_array($result4)){
            array_push($deniedCountArray, $row["total"]);
        }

        $startdate->modify('+1 day');
        $sdate = $startdate->format("Y-m-d");
    }
    ?>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>

    var dateArrayJS = <?php echo json_encode($dateArray);?>;
    var totalCountArrayJS = <?php echo json_encode($totalCountArray);?>;
    var entryCountArrayJS = <?php echo json_encode($entryCountArray);?>;
    var exitCountArrayJS = <?php echo json_encode($exitCountArray);?>;
    var deniedCountArrayJS = <?php echo json_encode($deniedCountArray);?>;

    // setup 
    let data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Entry & Exit Flows',
        data: totalCountArrayJS,
        backgroundColor: 'rgba(50, 205, 50, 0.2)',
        borderColor: 'rgba(50, 205, 50, 1)',
        borderWidth: 1
        }]
    };

    // config 
    let config = {
        type: 'line',
        data,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        }
    };

    
    // render init block
    let myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

     // setup 
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Entry Flows',
        data: entryCountArrayJS,
        backgroundColor: 'rgba(50, 205, 50, 0.2)',
        borderColor: 'rgba(50, 205, 50, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'line',
        data,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        }
    };

    
    // render init block
    let myChart2 = new Chart(
        document.getElementById('myChart2'),
        config
    );

     // setup 
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Exit Flows',
        data: exitCountArrayJS,
        backgroundColor: 'rgba(50, 205, 50, 0.2)',
        borderColor: 'rgba(50, 205, 50, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'line',
        data,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        }
    };

    
    // render init block
    let myChart3 = new Chart(
        document.getElementById('myChart3'),
        config
    );

     // setup 
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Denied Access Flows',
        data: deniedCountArrayJS,
        backgroundColor: 'rgba(50, 205, 50, 0.2)',
        borderColor: 'rgba(50, 205, 50, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'line',
        data,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        }
    };

    
    // render init block
    let myChart4 = new Chart(
        document.getElementById('myChart4'),
        config
    );

    // Instantly assign Chart.js version
    var chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;
    </script>

</body>
</html>