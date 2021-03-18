<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="materialize.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="style.css"  media="screen,projection"/>
    <title>CRUD-Project-Manager-PHP</title>
</head>
<body>
    <?php
        $servername = 'localhost';
        $username = 'root';
        $password = 'mysql';
        $dbname = 'projects';

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if(!$conn)
        die("Connection failed: ". mysqli_connect_error());

        if(isset($_POST['create_employee'])){
            $stmt = $conn->prepare('INSERT INTO darbuotojai (fname, lname) VALUES (?, ?)');
            $stmt->bind_param('ss', $fname, $lname);
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            
            $stmt->execute();
            $stmt->close();
            header('Location: ' . $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            die;
        }

    $sql = 'SELECT * FROM darbuotojai';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "id: " . $row["id"]. " - Name: " . $row["fname"]. " " . $row["lname"]. " " . " ";
          print("<button>DELETE</button><br>");
        }
    } else {
        echo "0 results";
    }
    
    $conn->close();
    ?>
    <br>
    <form action="" method="POST">
        <label for="fname">Name:</label><br>
        <input type="text" id="fname" name="fname" value=""><br>
        <label for="lname">Surname:</label><br>
        <input type="text" id="lname" name="lname" value=""><br>
        <input type="submit" name="create_employee" value="Submit">
    </form>
</body>
</html>