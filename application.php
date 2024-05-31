<?php
include 'config.php';

if(isset($_POST['submit'])){
    $ApplicationID = mysqli_real_escape_string($conn, $_POST['ApplicationID']);
    $applicant_name = mysqli_real_escape_string($conn, $_POST['nameofapplicant']);
    $Gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $NID_number = mysqli_real_escape_string($conn, $_POST['NID_number']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $applyingFrom = mysqli_real_escape_string($conn, $_POST['applyingFrom']);

    // Using prepared statements to prevent SQL injection and handle errors better
    $select_stmt = $conn->prepare("SELECT * FROM application WHERE ApplicationID = ? AND NID_number = ?");
    $select_stmt->bind_param("ss", $ApplicationID, $NID_number);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if($result->num_rows > 0){
        $message[] = 'Applicant already exists!';
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO application (ApplicationID, nameofapplicant, Gender, NID_number, Email, applyingFrom) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssss", $ApplicationID, $applicant_name, $Gender, $NID_number, $Email, $applyingFrom);
        
        if($insert_stmt->execute()){
            $message[] = 'Application completed successfully!';
        } else {
            die('Query failed: ' . mysqli_error($conn));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if(isset($message)) {
        foreach($message as $msg){
            echo '<div class="message" onclick="this.remove();">'.$msg.'</div>';
        }
    }
    ?>
<div class="form-container">
    <form action="" method="post">
        <h3>Application Form</h3>
        <input type="text" name="ApplicationID" required placeholder="Enter Your Birth ID" class="box">
        <input type="name" name="nameofapplicant" required placeholder="Enter your name" class="box">
        <select name="Gender" required class="box">
            <option value="" disabled selected>Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="NID_number" required placeholder="Enter your NID number" class="box">
        <input type="email" name="Email" required placeholder="Enter your Email" class="box">
        <select name="applyingFrom" required class="box">
            <option value="" disabled selected>Select Division</option>
            <option value="District 1">Dhaka</option>
            <option value="District 2">Chittagong</option>
            <option value="District 3">Sylhet</option>
            <option value="District 4">Barishal</option>
            <option value="District 5">Khulna</option>
            <option value="District 3">Rajshahi</option>
            <option value="District 4">Rangpur</option>
            <option value="District 5">Mymemsingh</option>
        </select>
        <input type="submit" name="submit" class="btn" style="color: white" value="Submit!">
    </form>
</div>
</body>
</html>