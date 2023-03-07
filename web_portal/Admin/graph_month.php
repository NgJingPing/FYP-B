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
    <button class="dbtn" onclick="downloadPDF2()">Download</button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart5"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart8"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart6"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart7"></canvas>
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
    $mtotalCountArray = $mdateArray = $mentryCountArray = $mexitCountArray = $mdeniedCountArray = array();

    $mstartdate = date('Y-m-d');
    $mstartdate = new DateTime($mstartdate);
    $mstartdate->modify('12 month ago');
    $menddate = date('Y-m-d');
    $menddate = new DateTime($menddate);;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}

    if(isset($_POST["submit"])) {
        if(empty($_POST["start"])) {
            // Do nothing
        } else {
            $mstartdate = new DateTime($_POST["start"]);
        }

        if(empty($_POST["end"])) {
            // Do nothing
        } else {
            $menddate = new DateTime($_POST["end"]);
        }
    }

    $menddate->modify('+1 month');

    $msdate = $mstartdate->format("m");
    $medate = $menddate->format("m");
    $msydate = $mstartdate->format("Y");

    //Monthly
    while($mstartdate->format('Y-m') != $menddate->format('Y-m')){
        $sql5 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE MONTH(`entryTime`) = '$msdate' AND YEAR(`entryTime`) = '$msydate') + (SELECT COUNT(*) FROM exitlog WHERE MONTH(`exitTime`) = '$msdate' AND YEAR(`exitTime`) = '$msydate') AS total;";
        $result5 = $conn->query($sql5);
        while($row = mysqli_fetch_array($result5)){
            array_push($mtotalCountArray, $row["total"]);
            array_push($mdateArray, $mstartdate->format("Y-m"));
        }

        $sql6 = "SELECT(SELECT COUNT(*) FROM entrylog WHERE MONTH(`entryTime`) = '$msdate' AND YEAR(`entryTime`) = '$msydate') AS total;";
        $result6 = $conn->query($sql6);
        while($row = mysqli_fetch_array($result6)){
            array_push($mentryCountArray, $row["total"]);
        }

        $sql7 = "SELECT(SELECT COUNT(*) FROM exitLog WHERE MONTH(`exitTime`) = '$msdate' AND YEAR(`exitTime`) = '$msydate') AS total;";
        $result7 = $conn->query($sql7);
        while($row = mysqli_fetch_array($result7)){
            array_push($mexitCountArray, $row["total"]);
        }

        $sql8 = "SELECT(SELECT COUNT(*) FROM deniedaccess WHERE MONTH(`deniedTime`) = '$msdate' AND YEAR(`deniedTime`) = '$msydate') AS total;";
        $result8 = $conn->query($sql8);
        while($row = mysqli_fetch_array($result8)){
            array_push($mdeniedCountArray, $row["total"]);
        }

        $mstartdate->modify('+1 month');
        $msdate = $mstartdate->format("m");
        $msydate = $mstartdate->format("Y");
    }

    ?>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js" integrity="sha512-ml/QKfG3+Yes6TwOzQb7aCNtJF4PUyha6R3w8pSTo/VJSywl7ZreYvvtUso7fKevpsI+pYVVwnu82YO0q3V6eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>

    var dateArrayJS = <?php echo json_encode($mdateArray);?>;
    var totalCountArrayJS = <?php echo json_encode($mtotalCountArray);?>;
    var entryCountArrayJS = <?php echo json_encode($mentryCountArray);?>;
    var exitCountArrayJS = <?php echo json_encode($mexitCountArray);?>;
    var deniedCountArrayJS = <?php echo json_encode($mdeniedCountArray);?>;

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
                x: {
                    type: 'time',
                    time: {
                        unit: 'month'
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
    let myChart5 = new Chart(
        document.getElementById('myChart5'),
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
                        unit: 'month'
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
    let myChart6 = new Chart(
        document.getElementById('myChart6'),
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
                        unit: 'month'
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
    let myChart7 = new Chart(
        document.getElementById('myChart7'),
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
                        unit: 'month'
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
    let myChart8 = new Chart(
        document.getElementById('myChart8'),
        config
    );

    // Instantly assign Chart.js version
    const chartVersion2 = document.getElementById('chartVersion');
    chartVersion2.innerText = Chart.version;

    function downloadPDF2(){
        var canvas5 = document.getElementById('myChart5');
        var canvas8 = document.getElementById('myChart8');
        var canvas6 = document.getElementById('myChart6');
        var canvas7 = document.getElementById('myChart7');

        var canvasImage5 = canvas5.toDataURL('image/jpeg', 1.0);
        var canvasImage8 = canvas8.toDataURL('image/jpeg', 1.0);
        var canvasImage6 = canvas6.toDataURL('image/jpeg', 1.0);
        var canvasImage7 = canvas7.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage5, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage8, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage6, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage7, 'JPEG', 30,30,250,155);
        pdf.save('analytics_month.pdf')
    }

    </script>

</body>
</html>
