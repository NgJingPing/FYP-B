<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">  
</head>

<body>
    <button class="dbtn" onclick="downloadPDF3()"><i class="fa fa-file-pdf-o"></i></button>
    <button class="dbtn" onclick="chartType('bar')"><span>Bar</span></button>
    <button class="dbtn" onclick="chartType('line')"><span>Line</span></i></button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard allflows">
                    <p>Flows</p>
                    <div class="chartBox2">
                        <canvas id="myChart20"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-12 col-lg-6 chartCard totalflows">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart9"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6 chartCard deniedflows">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart12"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-12 col-lg-6 chartCard entryflows">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart10"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-6 chartCard exitflows">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart11"></canvas>
                    </div>
                </div>
            </div>  
        </div>
    </div>

     <?php
    include "../include/config.php";
    $ytotalCountArray = $ydateArray = $yentryCountArray = $yexitCountArray = $ydeniedCountArray = array(); 

    $ystartdate = date('Y-m-d');
    $ystartdate = new DateTime($ystartdate);
    $ystartdate->modify('5 year ago');
    $yenddate = date('Y-m-d');
    $yenddate = new DateTime($yenddate);

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

        $sql11 = "SELECT(SELECT COUNT(*) FROM exitlog WHERE YEAR(`exitTime`) = '$ysdate') AS total;";
        $result11 = $conn->query($sql11);
        while($row = mysqli_fetch_array($result11)){
            array_push($yexitCountArray, $row["total"]);
        }

        $sql12 = "SELECT(SELECT COUNT(*) FROM deniedAccess WHERE YEAR(`deniedTime`) = '$ysdate') AS total;";
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

    var x = [];

    for(let i = 0; i < ydateArrayJS.length; i++) {
        x.push({
            day: ydateArrayJS[i], 
            total: ytotalCountArrayJS[i],
            entry: yentryCountArrayJS[i],
            exit: yexitCountArrayJS[i],
            denied: ydeniedCountArrayJS[i]
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
        backgroundColor: 'rgba(238, 75, 43, 0.2)',
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
                        unit: 'year',
                        tooltipFormat: 'yyyy',
                        displayFormats: {
                            day: 'yyyy'
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
    let myChart20 = new Chart(
        document.getElementById('myChart20'),
        config
    );

    // Onclick function for the Flows chart
    function clickHandler20(click){
        var points = myChart20.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart20.data.labels[index];
            var value = myChart20.data.datasets[dataset].data[index].day;
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + value,"report");

        }
    }

    myChart20.canvas.onclick = clickHandler20;

     // setup 
     //Data for the Total Flows chart
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
                        unit: 'year',
                        tooltipFormat: 'yyyy',
                        displayFormats: {
                            day: 'yyyy'
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
    let myChart9 = new Chart(
        document.getElementById('myChart9'),
        config
    );

    // Onclick function for the Total Flows chart
    function clickHandler9(click){
        var points = myChart9.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart9.data.labels[index];
            var value = myChart9.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart9.canvas.onclick = clickHandler9;

    // setup 
    //Data for the Entry Flows chart
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Entry Flows',
        data: yentryCountArrayJS,
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
                        unit: 'year',
                        tooltipFormat: 'yyyy',
                        displayFormats: {
                            day: 'yyyy'
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
    // Total Entry chart
    let myChart10 = new Chart(
        document.getElementById('myChart10'),
        config
    );

    // Onclick function for the Entry Flows chart
    function clickHandler10(click){
        var points = myChart10.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart10.data.labels[index];
            var value = myChart10.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart10.canvas.onclick = clickHandler10;

     // setup 
     //Data for the Exit Flows chart
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Exit Flows',
        data: yexitCountArrayJS,
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
                        unit: 'year',
                        tooltipFormat: 'yyyy',
                        displayFormats: {
                            day: 'yyyy'
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
    let myChart11 = new Chart(
        document.getElementById('myChart11'),
        config
    );

    // Onclick function for the Exit Flows chart
    function clickHandler11(click){
        var points = myChart11.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart11.data.labels[index];
            var value = myChart11.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart11.canvas.onclick = clickHandler11;

     // setup 
     //Data for the Denied Access Flows chart
    data = {
        labels: ydateArrayJS,
        datasets: [{
        label: 'Total Denied Access Flows',
        data: ydeniedCountArrayJS,
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
                        unit: 'year',
                        tooltipFormat: 'yyyy',
                        displayFormats: {
                            day: 'yyyy'
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
    let myChart12 = new Chart(
        document.getElementById('myChart12'),
        config
    );

    // Onclick function for the Denied Access Flows chart
    function clickHandler12(click){
        var points = myChart12.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart12.data.labels[index];
            var value = myChart12.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart12.canvas.onclick = clickHandler12;

    // Instantly assign Chart.js version
    var chartVersion3 = document.getElementById('chartVersion');
    chartVersion3.innerText = Chart.version;

    // Function for download the charts into PDF file
    function downloadPDF3(){
        var canvas9 = document.getElementById('myChart9');
        var canvas12 = document.getElementById('myChart12');
        var canvas10 = document.getElementById('myChart10');
        var canvas11 = document.getElementById('myChart11');
        var canvas20 = document.getElementById('myChart20');

        var canvasImage9 = canvas9.toDataURL('image/jpeg', 1.0);
        var canvasImage12 = canvas12.toDataURL('image/jpeg', 1.0);
        var canvasImage10 = canvas10.toDataURL('image/jpeg', 1.0);
        var canvasImage11 = canvas11.toDataURL('image/jpeg', 1.0);
        var canvasImage20 = canvas20.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage20, 'JPEG', 30,30,250,155);
        pdf.addPage()
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

