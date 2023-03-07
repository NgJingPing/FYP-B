<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        .dbtn {
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 2px 16px rgba(0,0,0,.1);
            width: fit-content;
            padding: 10px;
            font-weight: bold;
            border: solid 1px;
            margin-left: 0;
            color: rgba(0, 100, 0, 1);
        }
        .dbtn i{
            font-size:30px;
        }
    </style>
    
</head>

<body>
    <button class="dbtn" onclick="downloadPDF4()"><i class="fa fa-file-pdf-o"></i></button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart13"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart16"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart14"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart15"></canvas>
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
    
    while($startdate <= $enddate){
        $sql = "SELECT(SELECT COUNT(*) FROM entrylog WHERE WEEK(`entryTime`) = WEEK('$sdate') AND YEAR(`entryTime`) = YEAR('$sdate')) + (SELECT COUNT(*) FROM exitlog WHERE WEEK(`exitTime`) = WEEK('$sdate') AND YEAR(`exitTime`) = YEAR('$sdate')) AS total;";
        $result = $conn->query($sql);
        $start = strtotime('last sunday', strtotime($sdate));
        $end = strtotime('next saturday', strtotime($sdate));
        $format = 'j M';
        $format2 = 'j M';
        $start_day = date($format, $start);
        $end_day = date($format2, $end);
        $date = $start_day . "-". $end_day;

        while($row = mysqli_fetch_array($result)){
            array_push($totalCountArray, $row["total"]);
            array_push($dateArray, $date);
        }

        $sql2 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE WEEK(`entryTime`) = WEEK('$sdate') AND YEAR(`entryTime`) = YEAR('$sdate')) AS total;";
        $result2 = $conn->query($sql2);
        while($row = mysqli_fetch_array($result2)){
            array_push($entryCountArray, $row["total"]);
        }

        $sql3 = "SELECT(SELECT COUNT(*) FROM exitlog WHERE WEEK(`exitTime`) = WEEK('$sdate') AND YEAR(`exitTime`) = YEAR('$sdate')) AS total;";
        $result3 = $conn->query($sql3);
        while($row = mysqli_fetch_array($result3)){
            array_push($exitCountArray, $row["total"]);
        }

        $sql4 = "SELECT(SELECT COUNT(*) FROM deniedaccess WHERE WEEK(`deniedTime`) = WEEK('$sdate') AND YEAR(`deniedTime`) = YEAR('$sdate')) AS total;";
        $result4 = $conn->query($sql4);
        while($row = mysqli_fetch_array($result4)){
            array_push($deniedCountArray, $row["total"]);
        }

        $startdate->modify('+7 day');
        $sdate = $startdate->format("Y-m-d");
    }
    ?>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJSywl7ZreYvvtUso7fKevpsI+pYVVwnu82YO0q3V6eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>

    var dateArrayJS = <?php echo json_encode($dateArray);?>;
    var totalCountArrayJS = <?php echo json_encode($totalCountArray);?>;
    var entryCountArrayJS = <?php echo json_encode($entryCountArray);?>;
    var exitCountArrayJS = <?php echo json_encode($exitCountArray);?>;
    var deniedCountArrayJS = <?php echo json_encode($deniedCountArray);?>;

    // setup 
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Entry & Exit Flows',
        data: totalCountArrayJS,
        backgroundColor: 'rgba(50, 205, 50, 0.2)',
        borderColor: 'rgba(50, 205, 50, 1)',
        borderWidth: 1
        }]
    };

    bgColor = {
        id: 'bgColor',
        beforeDraw: (chart, options) => {
            const {ctx, width, height} = chart;
            ctx.fillStyle = 'white';
            ctx.fillRect(0,0, width, height)
            ctx.restore();
        }
    }

    // config 
    config = {
        type: 'line',
        data,
        options: {
            scales: {
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        },
        plugins: [bgColor]
    };

    
    // render init block
    let myChart13 = new Chart(
        document.getElementById('myChart13'),
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
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        },
        plugins: [bgColor]
    };

    
    // render init block
    let myChart14 = new Chart(
        document.getElementById('myChart14'),
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
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        },
        plugins: [bgColor]
    };

    
    // render init block
    let myChart15 = new Chart(
        document.getElementById('myChart15'),
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
                y: {
                    ticks: {
                        precision: 0,
                        beginAtZero: true
                    }
                }
            }
        },
        plugins: [bgColor]
    };

    
    // render init block
    let myChart16 = new Chart(
        document.getElementById('myChart16'),
        config
    );

    // Instantly assign Chart.js version
    var chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;

    function downloadPDF4(){
        var canvas13 = document.getElementById('myChart13');
        var canvas16 = document.getElementById('myChart16');
        var canvas14 = document.getElementById('myChart14');
        var canvas15 = document.getElementById('myChart15');

        var canvasImage13 = canvas13.toDataURL('image/jpeg', 1.0);
        var canvasImage16 = canvas16.toDataURL('image/jpeg', 1.0);
        var canvasImage14 = canvas14.toDataURL('image/jpeg', 1.0);
        var canvasImage15 = canvas15.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage13, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage16, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage14, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage15, 'JPEG', 30,30,250,155);
        pdf.save('analytics_week.pdf')
    }

    </script>

</body>
</html>