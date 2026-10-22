<?php
require "db.php";
$message = "";
$messageType="";

// This code handles the form submission for student registration. 
// It checks if the request method is POST, retrieves and trims the input values, and performs validation checks.
// If any validation fails, it sets an error message. 
// If all validations pass, it attempts to insert the student data into the database using a prepared statement.
// If the insertion is successful, it sets a success message; otherwise, it handles any exceptions that may occur during the database operation. 
// The messages are then displayed on the HTML page.

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $fullName=trim($_POST["full_name"]?? "");
    $email=trim($_POST["email"]?? "");
    $course=trim($_POST["course"]?? "");
    $level=trim($_POST["level"]?? "");

    if(
        $fullName===""||
        $email===""||
        $course===""||
        $level===""
    ){
        $message="Please fill in all fields.";
        $messageType="error";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        /*- filter_var() checks whether the email follows a valid email format.
        - FILTER_VALIDATE_EMAIL tells PHP to validate an email address.
        - ! means “not,” so the condition is true when the email format is invalid.
        - The elseif handles that case and sets an error message. Because the code uses an if / elseif / else structure, it won't proceed to the database insertion when the email fails validation.
        Remember: this checks the email format, not whether the email inbox actually exists.*/

        $message="Please enter a valid email address.";
        $messageType="error";
    }elseif(!in_array($level,["100","200","300","400","500"],true)){
        $message="Please select a valid academic level.";
        $messageType="error";
    }else{
        try{
            //This inserts the values into your existing students table. MySQL generates the id and created_at automatically.
            $sql = "INSERT INTO students 
            (fullname,email,course,LEVEL)
            VALUES(:full_name,:email,:course, :level)";

            /* Prepare the SQL statement to prevent SQL injection.
            PDO prepares the SQL query and binds the submitted values separately. 
            This helps protect your application against SQL injection.*/

            $stmt=$pdo->prepare($sql);
            // Execute the statement with the provided values
            $stmt->execute([
                ":full_name"=>$fullName,
                ":email"=>$email,
                ":course"=>$course,
                ":level" => $level
            ]);
            $message="Student registered successfully.";
            $messageType="success";
        } /*catch(PDOException $e){
            if($e->getCode()==="23000"){
                $message="Please select a valid academic level.";
                $messageType="error";
            }else
            {
                $message="something is wrong. Please try again.";
                $messageType="error";
            }
                }
                */
             catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Handle duplicate entry error (unique constraint violation)
                $message = "A student with this email already exists.";
                $messageType = "error";
            } else {
             error_log($e->getMessage()); // Check Laragon/PHP's error log
             $message = "Registration failed. Check the PHP error log.";
             $messageType = "error";
            }
             }
        
    }
}
?>


<!-- The above PHP code handles the form submission for student registration. It checks if the request method is POST, retrieves and trims the input values, and performs validation checks. If any validation fails, it sets an error message. If all validations pass, it attempts to insert the student data into the database using a prepared statement. If the insertion is successful, it sets a success message; otherwise, it handles any exceptions that may occur during the database operation. The messages are then displayed on the HTML page. -->
<!-- The HTML form below collects student details. -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
   <link rel="stylesheet" href="style2.css">
</head>
<body>

    <h1>Student Registration</h1>
    
    <div class="dashboard">

    <aside class="sidebar">
        <div class="logo">STUDENT HUB</div>

        <p class="nav-label">MAIN MENU</p>

        <nav>
            <a href="index.php" class="active">
                Register Student
            </a>

            <a href="students.php">
                Student Records
            </a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1 class="page-title">Register Student</h1>
                <p class="breadcrumb">
                    Dashboard / Register Student
                </p>
            </div>

            <div class="user-info">Admin</div>
        </header>

        <section class="panel">
            <h2>Student Information</h2>

            <p class="panel-description">
                Enter the student's details to register a new record.
            </p>
            

    <!-- This lets the page show either a success message or a validation error. -->
        <?php if ($message !== ""): ?>
             <p class="message <?= htmlspecialchars($messageType) ?>">
             <?= htmlspecialchars($message) ?>
             </p>
        <?php endif; ?>

            <form action="index.php" method="POST">

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="course">Course:</label>
        <input type="text" id="course" name="course" required>

        <br><br>

        <label for="level">Level:</label>
        <select id="level" name="level" required>
            <option value="">Select level</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="300">300</option>
            <option value="400">400</option>
            <option value="500">500</option>
        </select>

        <br><br>

        <button type="submit">Register Student</button>

    </form>
    

            <!-- Put your existing form here -->

        </section>

    </main>

</div>

    
</body>
</html>
