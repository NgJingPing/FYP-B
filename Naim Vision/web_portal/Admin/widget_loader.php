<?php
include "../include/config.php";

$totalflowquery = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = CURDATE()) + (SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = CURDATE()) AS total";
$result = $conn->query($totalflowquery);

$totalentryquery = "SELECT COUNT(*) AS totalentry FROM entrylog WHERE DATE(`entryTime`) = CURDATE()";
$result2 = $conn->query($totalentryquery);

$totalexitquery = "SELECT COUNT(*) AS totalexit FROM exitlog WHERE DATE(`exitTime`) = CURDATE()";
$result3 = $conn->query($totalexitquery);

$totaldeniedquery = "SELECT COUNT(*) AS totaldenied FROM deniedAccess WHERE DATE(`deniedTime`) = CURDATE()";
$result4 = $conn->query($totaldeniedquery);

echo '<div class="widget_group">';
if($result->num_rows > 0) {
	echo '<div class="widget_container">
            <div class="widget_name">
                <p>Total Flow Today</p>
            </div>
            <i class="fa-solid fa-right-left"></i>
            <div class="widget_value">';
                while ($row = mysqli_fetch_array($result)) {
                    echo '<div style="display:none;" class="totalflow">'.$row['total'].'</div>';
                    echo "<p>" . $row['total'] . "</p>";
                }
     echo'</div>';      
     echo'</div>';  
}

if($result2->num_rows > 0){
    echo '<div class="widget_container">
            <div class="widget_name">
                <p>Entries Today</p>
            </div>
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
            <div class="widget_value">';
                while ($row = mysqli_fetch_array($result2)) {
                    echo '<div style="display:none;" class="entryflow">'.$row['totalentry'].'</div>';
                    echo "<p>" . $row['totalentry'] . "</p>";
                }
    echo '</div>';
    echo '</div>';
}

if($result3->num_rows > 0){
    echo '<div class="widget_container">
            <div class="widget_name">
                <p>Exits Today</p>
            </div>
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <div class="widget_value">';
                while ($row = mysqli_fetch_array($result3)) {
                    echo '<div style="display:none;" class="exitflow">'.$row['totalexit'].'</div>';
                    echo "<p>" . $row['totalexit'] . "</p>";
                }
     echo '</div>';
     echo '</div>';
}

if($result4->num_rows > 0){
    echo '<div class="widget_container">
            <div class="widget_name">
                <p>Denied Entries</p>
            </div>
            <i class="fa-solid fa-ban"></i>
            <div class="widget_value">';
                while ($row = mysqli_fetch_array($result4)) {
                    echo '<div style="display:none;" class="deniedflow">'.$row['totaldenied'].'</div>';
                    echo "<p>" . $row['totaldenied'] . "</p>";
                }
     echo '</div>';
     echo '</div>';
}
echo '</div>';
?>