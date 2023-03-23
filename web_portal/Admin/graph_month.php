<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">
    
</head>

<body>
    <button class="dbtn" onclick="downloadPDF2()"><i class="fa fa-file-pdf-o"></i></button>
    <button class="dbtn" onclick="chartType('bar')"><span>Bar</span></button>
    <button class="dbtn" onclick="chartType('line')"><span>Line</span></i></button>
    <div class="table-responsive">
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard allflows">
                    <p>Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart19"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard totalflows">
                    <p>Total Entry & Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart5"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard deniedflows">
                    <p>Total Denied Access Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart8"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-lg- m-1 d-flex justify-content-center">
            <div class="row">
                <div class="col-sm-6 col-lg-6 chartCard entryflows">
                    <p>Total Entry Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart6"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 chartCard exitflows">
                    <p>Total Exit Flows</p>
                    <div class="chartBox">
                        <canvas id="myChart7"></canvas>
                    </div>
                </div>
            </div>  
        </div>
    </div>

     <?php
    include "../include/config.php";
    $mtotalCountArray = $mdateArray = $mentryCountArray = $mexitCountArray = $mdeniedCountArray = array();

    $mstartdate = date('Y-m-d');
    $mstartdate = new DateTime($mstartdate);
    $mstartdate->modify('12 month ago');
    $menddate = date('Y-m-d');
    $menddate = new DateTime($menddate);;

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
                bgColor,
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
                        unit: 'month'
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
        }
    };

    // render init block
    // Flows chart
    let myChart19 = new Chart(
        document.getElementById('myChart19'),
        config
    );

    // Onclick function for the Flows chart
    function clickHandler19(click){
        var points = myChart19.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart19.data.labels[index];
            var value = myChart19.data.datasets[dataset].data[index].day;
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + value,"report");

        }
    }

    myChart19.canvas.onclick = clickHandler19;

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
                bgColor,
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
        }
    };

    
    // render init block
    // Total Flows chart
    let myChart5 = new Chart(
        document.getElementById('myChart5'),
        config
    );

    // Onclick function for the Total Flows chart
    function clickHandler5(click){
        var points = myChart5.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart5.data.labels[index];
            var value = myChart5.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart5.canvas.onclick = clickHandler5;

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
                bgColor,
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
        }
    };

    
    // render init block
    // Entry Flows chart
    let myChart6 = new Chart(
        document.getElementById('myChart6'),
        config
    );

    // Onclick function for the Entry Flows chart
    function clickHandler6(click){
        var points = myChart6.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart6.data.labels[index];
            var value = myChart6.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart6.canvas.onclick = clickHandler6;

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
                bgColor,
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
        }
    };

    
    // render init block
    // Exit Flows chart
    let myChart7 = new Chart(
        document.getElementById('myChart7'),
        config
    );

    // Onclick function for the Exit Flows chart
    function clickHandler7(click){
        var points = myChart7.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart7.data.labels[index];
            var value = myChart7.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart7.canvas.onclick = clickHandler7;

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
                bgColor,
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
        }
    };

    
    // render init block
    // Denied Access Flows chart
    let myChart8 = new Chart(
        document.getElementById('myChart8'),
        config
    );

    // Onclick function for the Denied Access Flows chart
    function clickHandler8(click){
        var points = myChart8.getElementsAtEventForMode(click, 'nearest', {intersect: true}, true);
        if(points[0]){
            var dataset = points[0].datasetIndex;
            var index = points[0].index;
            var label = myChart8.data.labels[index];
            var value = myChart8.data.datasets[dataset].data[index];
            console.log(label);
            console.log(value);

            window.open("report.php?label=" + label,"report");

        }
    }

    myChart8.canvas.onclick = clickHandler8;

    // Instantly assign Chart.js version
    const chartVersion2 = document.getElementById('chartVersion');
    chartVersion2.innerText = Chart.version;

    // Function for download the charts into PDF file
    function downloadPDF2(){
        var canvas5 = document.getElementById('myChart5');
        var canvas8 = document.getElementById('myChart8');
        var canvas6 = document.getElementById('myChart6');
        var canvas7 = document.getElementById('myChart7');
        var canvas19 = document.getElementById('myChart19');

        var canvasImage5 = canvas5.toDataURL('image/jpeg', 1.0);
        var canvasImage8 = canvas8.toDataURL('image/jpeg', 1.0);
        var canvasImage6 = canvas6.toDataURL('image/jpeg', 1.0);
        var canvasImage7 = canvas7.toDataURL('image/jpeg', 1.0);
        var canvasImage19 = canvas19.toDataURL('image/jpeg', 1.0);

        let pdf = new jsPDF('landscape');
        pdf.setFontSize(20);
        pdf.addImage(canvasImage19, 'JPEG', 30,30,250,155);
        pdf.addPage()
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

