<?php

require "db.php";

$search= trim($_GET["search"]??"");

if($search !==""){
$sql= "SELECT * FROM students 
WHERE full_name LIKE :name 
OR email LIKE :email
 OR course LIKE :course
 OR LEVEL LIKE :level
 ORDER BY created_at DESC";

 $stmt = $pdo->prepare($sql);

 $keyword ="%" . $search . "%";

 $stmt->execute([
    ":name" => $keyword,
    ":email" =>$keyword,
    ":course"=>$keyword,
    ":level"=>$keyword
 ]);

}else {

$sql = "SELECT * FROM students ORDER BY created_at DESC";

$stmt = $pdo->query($sql);

}

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registered Students</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="dashboard">

        <aside class="sidebar">
            <h2 class="logo">Student Portal</h2>

            <nav>
                <a href="index.php">Register Student</a>
                <a href="students.php">View Students</a>
            </nav>
        </aside>

        <main class="main-content">

            <header class="topbar">
                <div>
                    <h1 class="page-title">Registered Students</h1>
                    <p class="breadcrumb">Dashboard / Students</p>
                </div>
            </header>

            <section class="panel">

                <h2>Student Records</h2>

                <p class="panel-description">
                    View all students registered in the system.
                </p>

                <div class="table-wrapper">

                <form action="students.php"  method="GET" class="search-form">
                    <input  type="search" name="search" placeholder="Search by name,email, or course" value="<?= htmlspecialchars($search) ?>">
                    
                    <button type="submit" class="btn">Search</button>

                    <?php if ($search !==""):?>
                       <a href="students.php" class="clear-search">Clear </a>   
                     <?php endif; ?>
                </form>
                       <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Level</th>
                                <th>Date Registered</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($students) > 0): ?>

                                <?php foreach ($students as $student): ?>

                                    <tr>
                                        <td><?= htmlspecialchars($student["id"]) ?></td>

                                        <td><?= htmlspecialchars($student["fullname"]) ?></td>

                                        <td><?= htmlspecialchars($student["email"]) ?></td>

                                        <td><?= htmlspecialchars($student["course"]) ?></td>

                                        <td><?= htmlspecialchars($student["LEVEL"]) ?></td>

                                        <td><?= htmlspecialchars($student["created_at"]) ?></td>
                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="6">No students registered yet.</td>
                                </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>

                </div>

            </section>

        </main>

    </div>

</body>
</html>