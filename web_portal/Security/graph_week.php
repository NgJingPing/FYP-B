<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">
    
</head>

<body>
    <button class="dbtn" onclick="downloadPDF4()"><i class="fa fa-file-pdf-o"></i></button>
    <button class="dbtn" onclick="chartType('bar')"><span>Bar</span></button>
    <button class="dbtn" onclick="chartType('line')"><span>Line</span></i></button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard allflows">
                    <p>Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart18"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard totalflows">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart13"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard deniedflows">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart16"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard entryflows">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart14"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard exitflows">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart15"></canvas>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <?php
    include "../include/config.php";
    $totalCountArray = $dateArray = $entryCountArray = $exitCountArray = $deniedCountArray = array();
    $startdate = date('Y-m-d');
    $startdate = new DateTime($startdate);
    $startdate->modify('1 month ago');
    $enddate = date('Y-m-d');
    $enddate = new DateTime($enddate);

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
        $format2 = 'j M Y';
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

    var x = [];

    for(let i = 0; i < dateArrayJS.length; i++) {
        x.push({
            day: dateArrayJS[i],
            total: totalCountArrayJS[i],
            entry: entryCountArrayJS[i],
            exit: exitCountArrayJS[i],
            denied: deniedCountArrayJS[i]
        });
    }

    Chart.defaults.font.family = "'Lato', Arial, Helvetica, sans-serif";

    //Data for the Flows chart
    data = {
        datasets: [{
        label: 'Entry Flows',
        data: x,
        backgroundColor: 'rgba(0, 150, 255, 0.2)',
        borderColor: 'rgba(0, 150, 255, 1)',
        borderWidth: 1,
        parsing: {
            yAxisKey: 'entry'
        }
        }, {
        label: 'Exit Flows',
        data: x,
        backgroundColor: 'rgba(255, 191, 0, 0.2)',
        borderColor: 'rgba(255, 191, 0, 1)',
        borderWidth: 1,
        parsing: {
            yAxisKey: 'exit'
        }
        }, {
        label: 'Denied Flows',
        data: x,
        backgroundColor: 'rgba(1238, 75, 43, 0.2)',
        borderColor: 'rgba(238, 75, 43, 1)',
        borderWidth: 1,
        parsing: {
            yAxisKey: 'denied'
        }
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
        type: 'bar',
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
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
                    yAlign: 'top'
                }
            },
            parsing: {
                    xAxisKey: 'day',
                },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
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
    // Flows chart
    let myChart18 = new Chart(
        document.getElementById('myChart18'),
        config
    );

    // Onclick function for the Flows chart
    function clickHandler18(click){
        var points = myChart18.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart18.data.labels[index];
            var value = myChart18.data.datasets[dataset].data[index].day;
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + value,"report");

        }
    }

    myChart18.canvas.onclick = clickHandler18;

    // setup 
    //Data for the Total Flows chart
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
        type: 'bar',
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {onClick: function() {}},
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
                    yAlign: 'top'
                }
            },
            onHover: (event, chartElement) => {
               event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
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
    // Total Flows chart
    let myChart13 = new Chart(
        document.getElementById('myChart13'),
        config
    );

    // Onclick function for the Total Flows chart
    function clickHandler13(click){
        var points = myChart13.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart13.data.labels[index];
            var value = myChart13.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart13.canvas.onclick = clickHandler13;

     // setup 
     //Data for the Entry Flows chart
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Entry Flows',
        data: entryCountArrayJS,
        backgroundColor: 'rgba(0, 150, 255, 0.2)',
        borderColor: 'rgba(0, 150, 255, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'bar',
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {onClick: function() {}},
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
                    yAlign: 'top'
                }
            },
            onHover: (event, chartElement) => {
               event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
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
    // Entry Flows chart
    let myChart14 = new Chart(
        document.getElementById('myChart14'),
        config
    );

    // Onclick function for the Entry Flows chart
    function clickHandler14(click){
        var points = myChart14.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart14.data.labels[index];
            var value = myChart14.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart14.canvas.onclick = clickHandler14;

     // setup 
     //Data for the Exit Flows chart
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Exit Flows',
        data: exitCountArrayJS,
        backgroundColor: 'rgba(255, 191, 0, 0.2)',
        borderColor: 'rgba(255, 191, 0, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'bar',
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {onClick: function() {}},
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
                    yAlign: 'top'
                }
            },
            onHover: (event, chartElement) => {
               event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
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
    // Exit Flows chart
    let myChart15 = new Chart(
        document.getElementById('myChart15'),
        config
    );

    // Onclick function for the Exit Flows chart
    function clickHandler15(click){
        var points = myChart15.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart15.data.labels[index];
            var value = myChart15.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart15.canvas.onclick = clickHandler15;

     // setup 
     //Data for the Denied Access Flows chart
    data = {
        labels: dateArrayJS,
        datasets: [{
        label: 'Total Denied Access Flows',
        data: deniedCountArrayJS,
        backgroundColor: 'rgba(238, 75, 43, 0.2)',
        borderColor: 'rgba(238, 75, 43, 1)',
        borderWidth: 1
        }]
    };

    // config 
    config = {
        type: 'bar',
        data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {onClick: function() {}},
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
                    yAlign: 'top'
                }
            },
            onHover: (event, chartElement) => {
               event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
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
    // Denied Access Flows chart
    let myChart16 = new Chart(
        document.getElementById('myChart16'),
        config
    );

    // Onclick function for the Denied Access Flows chart
    function clickHandler16(click){
        var points = myChart16.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart16.data.labels[index];
            var value = myChart16.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart16.canvas.onclick = clickHandler16;

    // Instantly assign Chart.js version
    var chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;

    // Function for download the charts into PDF file
    function downloadPDF4(){
        var canvas13 = document.getElementById('myChart13');
        var canvas16 = document.getElementById('myChart16');
        var canvas14 = document.getElementById('myChart14');
        var canvas15 = document.getElementById('myChart15');
        var canvas18 = document.getElementById('myChart18');

        var canvasImage13 = canvas13.toDataURL('image/jpeg', 1.0);
        var canvasImage16 = canvas16.toDataURL('image/jpeg', 1.0);
        var canvasImage14 = canvas14.toDataURL('image/jpeg', 1.0);
        var canvasImage15 = canvas15.toDataURL('image/jpeg', 1.0);
        var canvasImage18 = canvas18.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage18, 'JPEG', 30,30,250,155);
        pdf.addPage()
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
