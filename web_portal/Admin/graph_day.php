<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">
    
</head>

<body>
    <button class="dbtn" onclick="downloadPDF()"><i class="fa fa-file-pdf-o"></i></button>
    <button class="dbtn" onclick="chartType('bar')"><span>Bar</span></button>
    <button class="dbtn" onclick="chartType('line')"><span>Line</span></i></button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard allflows">
                    <p>Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart17"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-12 col-lg-6 chartCard totalflows">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6 chartCard deniedflows">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-12 col-lg-6 chartCard entryflows">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6 chartCard exitflows">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart3"></canvas>
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
    $startdate->modify('7 day ago');
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

        $sql4 = "SELECT(SELECT COUNT(*) FROM deniedAccess WHERE DATE(`deniedTime`) = '$sdate') AS total;";
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
    let data = {
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
        backgroundColor: 'rgba(238, 75, 43, 0.2)',
        borderColor: 'rgba(238, 75, 43, 1)',
        borderWidth: 1,
        parsing: {
            yAxisKey: 'denied'
        }
        }]
    };

    let bgColor = {
        id: 'bgColor',
        beforeDraw: (chart, options) => {
            const {ctx, width, height} = chart;
            ctx.fillStyle = 'white';
            ctx.fillRect(0,0, width, height)
            ctx.restore();
        }
    }

    // config 
    let config = {
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
            onHover: (event, chartElement) => {
               event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            },
            parsing: {
                    xAxisKey: 'day',
                },
            scales: {
                 x: {
                    stacked: true,
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: {
                            day: 'dd MMM'
                        }
                    }
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
    let myChart17 = new Chart(
        document.getElementById('myChart17'),
        config
    );

    // Onclick function for the Flows chart
    function clickHandler17(click){
        var points = myChart17.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart17.data.labels[index];
            var value = myChart17.data.datasets[dataset].data[index].day;
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + value,"report");

        }
    }

    myChart17.canvas.onclick = clickHandler17;

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
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: {
                            day: 'dd MMM'
                        }
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
    // Total Flows chart
    myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

    // Onclick function for the Total Flows chart
    function clickHandler(click){
        var points = myChart.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart.data.labels[index];
            var value = myChart.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart.canvas.onclick = clickHandler;

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
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: {
                            day: 'dd MMM'
                        }
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
    // Entry Flows chart
    let myChart2 = new Chart(
        document.getElementById('myChart2'),
        config
    );

    // Onclick function for the Entry Flows chart
    function clickHandler2(click){
        var points = myChart2.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart2.data.labels[index];
            var value = myChart2.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart2.canvas.onclick = clickHandler2;

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
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: {
                            day: 'dd MMM'
                        }
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
    // Exit Flows chart
    let myChart3 = new Chart(
        document.getElementById('myChart3'),
        config
    );

    // Onclick function for the Exit Flows chart
    function clickHandler3(click){
        var points = myChart3.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart3.data.labels[index];
            var value = myChart3.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart3.canvas.onclick = clickHandler3;

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
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy',
                        displayFormats: {
                            day: 'dd MMM'
                        }
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
    // Denied Access Flows chart
    let myChart4 = new Chart(
        document.getElementById('myChart4'),
        config
    );

    // Onclick function for the Denied Access Flows chart
    function clickHandler4(click){
        var points = myChart4.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart4.data.labels[index];
            var value = myChart4.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart4.canvas.onclick = clickHandler4;

    // Instantly assign Chart.js version
    var chartVersion = document.getElementById('chartVersion');
    chartVersion.innerText = Chart.version;

    // Function for download the charts into PDF file
    function downloadPDF(){
        var canvas = document.getElementById('myChart');
        var canvas4 = document.getElementById('myChart4');
        var canvas2 = document.getElementById('myChart2');
        var canvas3 = document.getElementById('myChart3');
        var canvas17 = document.getElementById('myChart17');

        var canvasImage = canvas.toDataURL('image/jpeg', 1.0);
        var canvasImage4 = canvas4.toDataURL('image/jpeg', 1.0);
        var canvasImage2 = canvas2.toDataURL('image/jpeg', 1.0);
        var canvasImage3 = canvas3.toDataURL('image/jpeg', 1.0);
        var canvasImage17 = canvas17.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage17, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage4, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage2, 'JPEG', 30,30,250,155);
        pdf.addPage()
        pdf.addImage(canvasImage3, 'JPEG', 30,30,250,155);
        pdf.save('analytics_day.pdf')
    }

    // function for updating the chart to either Bar or Line when the button is being clicked
    function chartType(type){
        myChart.config.type = type;
        myChart.update()

        myChart2.config.type = type;
        myChart2.update()

        myChart3.config.type = type;
        myChart3.update()

        myChart4.config.type = type;
        myChart4.update()

        myChart17.config.type = type;
        myChart17.update()

        myChart5.config.type = type;
        myChart5.update()

        myChart6.config.type = type;
        myChart6.update()

        myChart7.config.type = type;
        myChart7.update()

        myChart8.config.type = type;
        myChart8.update()

        myChart19.config.type = type;
        myChart19.update()

        myChart9.config.type = type;
        myChart9.update()

        myChart10.config.type = type;
        myChart10.update()

        myChart11.config.type = type;
        myChart11.update()

        myChart12.config.type = type;
        myChart12.update()

        myChart20.config.type = type;
        myChart20.update()

        myChart13.config.type = type;
        myChart13.update()

        myChart14.config.type = type;
        myChart14.update()

        myChart15.config.type = type;
        myChart15.update()

        myChart16.config.type = type;
        myChart16.update()

        myChart18.config.type = type;
        myChart18.update()
    }

    </script>

</body>
</html>
