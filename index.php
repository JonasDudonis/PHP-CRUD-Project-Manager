<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="materialize.css" media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="style.css" media="screen,projection"/>
    <title>CRUD-Project-Manager-PHP</title>
</head>
<body>
    <?php
        $servername = 'localhost';
        $username = 'root';
        $password = 'mysql';
        $dbname = 'projects';
        $table = 'darbuotojai';

        if(isset($_GET['path']) and $_GET['path'] !== $table){
            if($_GET['path'] == 'darbuotojai' or $_GET['path'] == 'projektai')
                $table = $_GET['path'];
        }

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if(!$conn)
        die("Connection failed: ". mysqli_connect_error());        

        // TASK 1 - READ TABLES

        // $sql = "SELECT " . $table. ".id, " . $table.".name, " . ($table === 'projektai' ? 'darbuotojai' : 'projektai' ) . ".name " . "FROM " . $table . 
        //     " LEFT JOIN " . ($table === 'projektai' ? 'darbuotojai' : 'projektai') . 
        //     " ON " . ($table === 'projektai' ? 'darbuotojai.proj_id = projektai.id' : 'darbuotojai.proj_id = projektai.id');
        
        // TASK 2 - AGGREGATION

        $sql = "SELECT " 
            . $table. ".id, " 
            . $table.".name, GROUP_CONCAT(" . ($table === 'projektai' ? 'darbuotojai' : 'projektai' ) . ".name SEPARATOR \", \")" . " FROM " . $table . 
        " LEFT JOIN " . ($table === 'projektai' ? 'darbuotojai' : 'projektai') . 
        " ON " . ($table === 'projektai' ? 'darbuotojai.proj_id = projektai.id' : 'darbuotojai.proj_id = projektai.id') .
        " GROUP BY " . $table . ".id;";

        // TERNARY STATEMENTS
        // if ($table === 'projektai') {return 'darbuotojai'} else (return 'projektai')
        // (Condition) ? (Statement1) : (Statement2);

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($id, $mainProjectName, $relatedEmployeeName);
?>
    <header>
        <div class="header">
            <a href="?path=projektai" class="" style="">Projekto valdymas</a>
            <ul id="nav-mobile" class="left">
            <li><a href="?path=projektai">Projektai</a></li>
            <li><a href="?path=darbuotojai">Darbuotojai</a></li>
            </ul>
        </div>
    </header>

    <main style="margin-left: 50px; margin-right: 50px">
    <?php
        echo '<table><th>Id</th><th>Name</th><th>' . ($table === 'projektai' ? 'Darbuotojai' : 'Projektai') . '</th>';
        while ($stmt->fetch()){
        echo "<tr><td>" . $id . "</td><td>" . $mainProjectName . "</td><td>" . $relatedEmployeeName . "</td></tr>";
    }
        echo '</table>';
    ?>

    <?php

        // if(isset($_POST['ADD'])){
        //     print($_POST['name']);
        //     $sql_add = "INSERT INTO " . $table . " (`name`) VALUES (?)"; 
        //     $stmt = $conn->prepare($sql_add);
        //     $stmt -> bind_param("s", $_POST['name']);
        //     $stmt->execute();
        //     header("Location: /ProjectManagerPHP/?path=" . $_GET['path']);
        // }

    // $sql = 'SELECT * FROM darbuotojai';
    // $result = $conn->query($sql);

    // if ($result->num_rows > 0) {
    //     while($row = $result->fetch_assoc()) {
    //       echo "id: " . $row["id"]. " - Name: " . $row["name"]. " ";
    //       print("<button>DELETE</button><br>");
    //     }
    // } else {
    //     echo "0 results";
    // }
    
    $conn->close();
    ?>

</main>
</body>
</html>
