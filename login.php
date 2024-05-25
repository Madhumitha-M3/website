<?php
$uname = $_POST['uname'];
$upswd = $_POST['upswd'];

if (!empty($uname) && !empty($upswd)) {
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "project";

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    } else {
        $SELECT = "SELECT register.uname1, login.uname, register.upswd1
FROM register
INNER JOIN login ON register.upswd1=login.upswd
WHERE register.uname1=?";


        $INSERT = "INSERT INTO register (uname1, upswd1) VALUES (?,?)";

        // Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;


        // Checking if the username is already registered
        if ($rnum == 0) {
            $stmt->close();

            // Prepare INSERT statement
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ss", $uname, $upswd); 
            $stmt->execute();

            echo "New record inserted successfully";
            // Redirect to another page
            header("Location: index.html");
            exit(); // Ensure script execution stops after redirection
        
        } else {
            echo "Someone already registered using this username";
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required";
    die();
}
?>