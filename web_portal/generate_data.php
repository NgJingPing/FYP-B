<!-- 

 WARNING !!! : This file only use for generate random data 
 purpose. It generates random data and insert to existing
 phpmyadmin "anprdb" database's table. Also, the existing data 
 will delete from the database after running this file. 

 How ? : This file need existing phpmyadmin "anprdb" database's 
 table with data or without data

-->

<!-- <h1>Connect to your phpMyAdmin</h1> -->
<?php
// set the servername,username and password
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anprdb";

// Create connection
//The mysqli_connect() function attempts to open a connection to the MySQL Server
//running on host which can be either a host name or an IP address.
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Disable foreign key checks
mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS=0');

// SQL query to delete all data from the table
$sql1 = "TRUNCATE TABLE tenant";
$sql2 = "TRUNCATE TABLE vehicle";
$sql3 = "TRUNCATE TABLE entrylog";
$sql4 = "TRUNCATE TABLE exitlog";
$sql5 = "TRUNCATE TABLE deniedAccess";

$remove_datas = [$sql1, $sql2, $sql3, $sql4, $sql5];

// Execute the query
foreach($remove_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "Tables have been emptied successfully.";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}

/// Generate tenant data
$sql1 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
VALUES ('AB1234', 'Mandy', '0123456789')";

$sql2 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
VALUES ('AB2345', 'Tony', '0123654987')";

$sql3 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
VALUES ('AB3456', 'Karen', '0132748596')";

$sql4 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
VALUES ('AB4567', 'John', '0142536998')";

$sql5 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
VALUES ('AB5678', 'Neon', '0165478932')";

$tenant_datas = [$sql1, $sql2, $sql3, $sql4, $sql5];

foreach($tenant_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}

/// Generate vehicle data
$sql1 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
VALUES ('QAB1234', 'AB1234', 'Honda', 'City', 'Purple', 0)";

$sql2 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
VALUES ('QAB2345', 'AB2345', 'Honda', 'Civic', 'White', 0)";

$sql3 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
VALUES ('QAB3456', 'AB3456', 'Proton', 'Saga', 'Brown', 0)";

$sql4 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
VALUES ('QAB4567', 'AB4567', 'Proton', 'Waja', 'Grey', 0)";

$sql5 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
VALUES ('QAB5678', 'AB5678', 'Toyota', 'Hilux', 'Black', 0)";

$vehicle_datas = [$sql1, $sql2, $sql3, $sql4, $sql5];

foreach($vehicle_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}

// Generate Tenant'phone number
function generateRandomPhoneNumber() {
    $phoneNumber = rand(10000000, 99999999);
    return "01" . $phoneNumber;
}

// Generate Tenant'name
function generateRandomHumanName() {
    $firstNames = array("James", "John", "Robert", "Michael", "William", "David", "Richard", "Joseph", "Charles", "Thomas", "Christopher", "Daniel", "Matthew", "Anthony", "Donald", "Mark", "Paul", "Steven", "Andrew", "Kenneth", "Joshua", "Kevin", "Brian", "George", "Edward", "Mary", "Patricia", "Jennifer", "Linda", "Elizabeth", "Barbara", "Susan", "Jessica", "Margaret", "Sarah", "Karen", "Nancy", "Betty", "Lisa", "Dorothy", "Sandra", "Ashley", "Kimberly", "Emily", "Donna", "Michelle", "Carol", "Amanda", "Melissa", "Deborah", "Stephanie");
    
    $lastNames = array("Smith", "Johnson", "Williams", "Jones", "Brown", "Davis", "Miller", "Wilson", "Moore", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin", "Thompson", "Garcia", "Martinez", "Robinson", "Clark", "Rodriguez", "Lewis", "Lee", "Walker", "Hall", "Allen", "Young", "King", "Wright", "Scott", "Green", "Baker", "Adams", "Nelson", "Carter", "Mitchell", "Perez", "Roberts", "Turner", "Phillips", "Campbell", "Parker", "Evans", "Edwards", "Collins", "Stewart", "Sanchez");
    
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    return $firstName . ' ' . $lastName ; // concatenate first name, last name
}

/// Insert extra 20 data for tenant and vehicle
for ($i = 1; $i <= 20; $i++) {
    /// Generate vehicle data

    // Generate vehicle's license plate
    $plate_number = "";

    // Generate three random alphabets
    for ($rpa = 1; $rpa <= 3; $rpa++) {
        $plate_number .= chr(rand(65, 90)); // ASCII values for A-Z
    }

    // Generate four random numbers
    for ($rpn = 1; $rpn <= 4; $rpn++) {
        $plate_number .= rand(0, 9);
    }

    // Generate Tenant's lot number
    $tenant_number = "";

    // Generate two random alphabets
    for ($rta = 1; $rta <= 2; $rta++) {
        $tenant_number .= chr(rand(65, 90)); // ASCII values for A-Z
    }

    // Generate four random numbers
    for ($rtn = 1; $rtn <= 4; $rtn++) {
        $tenant_number .= rand(0, 9);
    }

    // Generate vehicle's colour
    $colors = array("Blue", "Black", "White", "Grey", "Purple", "Silver");
    $random_color_index = array_rand($colors);
    $random_color = $colors[$random_color_index];


    // Generate vehicle (Brand and model)
    $cars = array(
        array("Toyota", "Hilux"),
        array("Proton", "Saga"),
        array("Tesla", "Model S")
    );

    $random_car_index = array_rand($cars);
    $random_brand = $cars[$random_car_index][0];
    $random_model = $cars[$random_car_index][1];

    
    $sql6 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour, isActive)
    VALUES ('$plate_number', '$tenant_number', '$random_brand', '$random_model', '$random_color', 1)";

    /// Generate tenant data   
    // Generate Tenant'name
    $uniqueName = generateRandomHumanName();
    
    // Generate Tenant'phone number
    $uniquePhoneNumber = generateRandomPhoneNumber();

    $sql7 = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber)
    VALUES ('$tenant_number', '$uniqueName', '$uniquePhoneNumber')";

    $extra_datas = [$sql6, $sql7];

    foreach($extra_datas as $sql){
        if (mysqli_query($conn, $sql)) {
            //echo "New record created successfully";
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

}

// Generate log's vehicleID
function generateRandomNumber($startnum, $endnum) {
    $randomNumber = rand($startnum, $endnum);
    return $randomNumber;
}

// Generate log's date time bewteen 2023 and 2028
function generateRandomDateTime() {
    $startTime = strtotime("2023-01-01 00:00:00");
    $endTime = strtotime("2028-12-31 23:59:59");
    $randomTime = rand($startTime, $endTime);
    $dateTime = date("Y-m-d H:i:s", $randomTime);
    return $dateTime;
}

/// Insert extra 600 data for entry log 
for ($a = 1; $a <= 600; $a++) {
    // Generate Entrylog's vehicleID
    $randomNumber = generateRandomNumber(6, 25);

    // Generate Entrylog's date time bewteen 2023 and 2028
    $randomDateTime = generateRandomDateTime();

    $sql8 = "INSERT INTO entryLog (vehicleID, entryTime, image, image_2)
    VALUES ('$randomNumber', '$randomDateTime', 'entrylog\\\generateData.jpg', 'entrylog\\\generateData_2.jpg')";


    if (mysqli_query($conn, $sql8)) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}

/// Insert extra 600 data for exit log 
for ($b = 1; $b <= 600; $b++) {
    // Generate Exitlog's vehicleID
    $randomNumber = generateRandomNumber(6, 25);

    // Generate Exitlog's date time bewteen 2023 and 2028
    $randomDateTime = generateRandomDateTime();

    $sql9 = "INSERT INTO exitlog (vehicleID, exitTime, image, image_2)
    VALUES ('$randomNumber', '$randomDateTime', 'exitlog\\\generateData.jpg', 'exitlog\\\generateData_2.jpg')";


    if (mysqli_query($conn, $sql9)) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}

// Get unique tenant license plate number data from existing vehicle table
$license_Plate_Array = array();

$sql10 = "SELECT DISTINCT licensePlate FROM vehicle";
$outcome = mysqli_query($conn, $sql10);

if (mysqli_num_rows($outcome) > 0) {
    while($row = mysqli_fetch_assoc($outcome)) {
        array_push($license_Plate_Array, $row["licensePlate"]);
    }
} else {
    //echo "0 results";
}

//print_r($license_Plate_Array);

/// Insert extra 300 data for denied access log 
for ($c = 1; $c <= 300; $c++) {
    // Generate tenant's vehicleID
    $random_LPA_index = generateRandomNumber(5, 24);
    // Generate Deniedaccess's licensePlate
    $random_licensePlate = $license_Plate_Array[$random_LPA_index];

    // Generate Exitlog's date time bewteen 2023 and 2028
    $randomDateTime = generateRandomDateTime();

    // Generate vehicle (Brand and model)
    $denied_images = array(
        array("entrylog\\\generateData.jpg", "entrylog\\\generateData_2.jpg"),
        array("exitlog\\\generateData.jpg", "exitlog\\\generateData_2.jpg")
    );

    $denied_images_index = array_rand($denied_images);
    $denied_image = $denied_images[$denied_images_index][0];
    $denied_image_2 = $denied_images[$denied_images_index][1];

    $sql11 = "INSERT INTO deniedAccess (licensePlate, deniedTime, image, image_2)
    VALUES ('$random_licensePlate', '$randomDateTime', '$denied_image', '$denied_image_2')";

    if (mysqli_query($conn, $sql11)) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

echo "<h2>Generate Data</h2>";
echo "<h3>✔ 25 Tenant data inserted successfully</h3>";
echo "<h3>✔ 25 Vehicle data inserted successfully</h3>";
echo "<h3>✔ 600 Entrylog data inserted successfully</h3>";
echo "<h3>✔ 600 Exitlog data inserted successfully</h3>";
echo "<h3>✔ 300 Deniedaccess data inserted successfully</h3>";

// Enable foreign key checks
mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS=1');

mysqli_close($conn);
//header("location:login.php");
exit();

?>