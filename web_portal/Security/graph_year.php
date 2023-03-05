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
            background-color: #919191;
            box-shadow: 0 2px 16px rgba(0,0,0,.1);
            width: fit-content;
            padding: 5px;
            font-weight: bold;
            border: solid 1px;
            margin-left: 0;
        }
    </style>
    
</head>

<body>
    <button class="dbtn" onclick="downloadPDF3()">Download</button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart9"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart12"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart10"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart11"></canvas>
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
    $ytotalCountArray = $ydateArray = $yentryCountArray = $yexitCountArray = $ydeniedCountArray = array();

    $ystartdate = date('Y-m-d');
    $ystartdate = new DateTime($ystartdate);
    $ystartdate->modify('5 year ago');
    $yenddate = date('Y-m-d');
    $yenddate = new DateTime($yenddate);

    $conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}

    if(isset($_POST["submit"])) {
        if(empty($_POST["start"])) {
            // Do nothing
        } else {
            $ystartdate = new DateTime($_POST["start"]);
        }

        if(empty($_POST["end"])) {
            // Do nothing
        } else {
            $yenddate = new DateTime($_POST["end"]);
        }
    }

    $yenddate->modify('+1 year');
    $ysdate = $ystartdate->format("Y");

    //Yearly
    while($ystartdate->format("Y") != $yenddate->format("Y")){
        $sql9 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE YEAR(`entryTime`) = '$ysdate') + (SELECT COUNT(*) FROM exitlog WHERE YEAR(`exitTime`) = '$ysdate') AS total;";
        $result9 = $conn->query($sql9);
        while($row = mysqli_fetch_array($result9)){
            array_push($ytotalCountArray, $row["total"]);
            array_push($ydateArray, $ystartdate->format("Y"));
        }

        $sql10 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE YEAR(`entryTime`) = '$ysdate') AS total;";
        $result10 = $conn->query($sql10);
        while($row = mysqli_fetch_array($result10)){
            array_push($yentryCountArray, $row["total"]);
        }

        $sql11 = "SELECT(SELECT COUNT(*) FROM exitLog WHERE YEAR(`exitTime`) = '$ysdate') AS total;";
        $result11 = $conn->query($sql11);
        while($row = mysqli_fetch_array($result11)){
            array_push($yexitCountArray, $row["total"]);
        }

        $sql12 = "SELECT(SELECT COUNT(*) FROM deniedaccess WHERE YEAR(`deniedTime`) = '$ysdate') AS total;";
        $result12 = $conn->query($sql12);
        while($row = mysqli_fetch_array($result12)){
            array_push($ydeniedCountArray, $row["total"]);
        }

        $ystartdate->modify('+1 year');
        $ysdate = $ystartdate->format("Y");
    }

    ?>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJSywl7ZreYvvtUso7fKevpsI+pYVVwnu82YO0q3V6eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>

    var ydateArrayJS = <?php echo json_encode($ydateArray);?>;
    var ytotalCountArrayJS = <?php echo json_encode($ytotalCountArray);?>;
    var yentryCountArrayJS = <?php echo json_encode($yentryCountArray);?>;
    var yexitCountArrayJS = <?php echo json_encode($yexitCountArray);?>;
    var ydeniedCountArrayJS = <?php echo json_encode($ydeniedCountArray);?>;

     // setup 
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Entry & Exit Flows',
        data: ytotalCountArrayJS,
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
                x: {
                    type: 'time',
                    time: {
                        unit: 'year'
                    }
                },
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
    let myChart9 = new Chart(
        document.getElementById('myChart9'),
        config
    );

    // setup 
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Entry Flows',
        data: yentryCountArrayJS,
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
                        unit: 'year'
                    }
                },
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
    let myChart10 = new Chart(
        document.getElementById('myChart10'),
        config
    );

     // setup 
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Exit Flows',
        data: yexitCountArrayJS,
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
                        unit: 'year'
                    }
                },
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
    let myChart11 = new Chart(
        document.getElementById('myChart11'),
        config
    );

     // setup 
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Denied Access Flows',
        data: ydeniedCountArrayJS,
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
                        unit: 'year'
                    }
                },
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
    let myChart12 = new Chart(
        document.getElementById('myChart12'),
        config
    );

    // Instantly assign Chart.js version
    var chartVersion3 = document.getElementById('chartVersion');
    chartVersion3.innerText = Chart.version;

    function downloadPDF3(){
        var canvas9 = document.getElementById('myChart9');
        var canvas12 = document.getElementById('myChart12');
        var canvas10 = document.getElementById('myChart10');
        var canvas11 = document.getElementById('myChart11');

        var canvasImage9 = canvas9.toDataURL('image/jpeg', 1.0);
        var canvasImage12 = canvas12.toDataURL('image/jpeg', 1.0);
        var canvasImage10 = canvas10.toDataURL('image/jpeg', 1.0);
        var canvasImage11 = canvas11.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage9, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage12, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage10, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage11, 'JPEG', 30,30,250,155);
        pdf.save('analytics_year.pdf')
    }

    </script>

</body>
</html>

