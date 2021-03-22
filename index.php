<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/materialize.css" media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/style.css" media="screen,projection"/>
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
        
        // DELETE LOGIC

        if(isset($_GET['delete'])){
            $del = "DELETE FROM " . $table . " WHERE id = " . $_GET['delete'];
            $stmt = $conn->prepare($del);
            $stmt->execute();
            header("Location: /PHP-CRUD-Project-Manager/?path=" . $_GET['path']);
        }


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
    <h1 style="text-align: center; color: black"><a href="/PHP-CRUD-Project-Manager/" class="" >Projekto valdymas</a></h1>
        <div class="header">
            
            <ul style="text-align: center; color: black">
            <li><a href="?path=projektai">Projektai</a></li>
            <li><a href="?path=darbuotojai">Darbuotojai</a></li>
            </ul>
        </div>
    </header>

    <main style="margin-left: 50px; margin-right: 50px;">
        <div class="table2">
        <?php
        echo '<table>
                <th>Id</th>
                <th>Name</th>
                <th>' . ($table === 'projektai' ? 'Darbuotojai' : 'Projektai') . '</th>';
        while ($stmt->fetch()){
        echo 
        "<tr>
        <td>" . $id . "</td>
        <td>" . $mainProjectName . "</td>
        <td>" . $relatedEmployeeName . "</td>
        <td>
        <button><a href=\"?path=" . $table . "&delete=$id\">DELETE</a></button>
        <button><a href=\"?path=" . $table . "&update=$id\">UPDATE</a></button>
        </td>
        </tr>"; 
    }
        echo '</table>';
?>
        </div>

            <?php

    // INSERT NEW EMPLOYEE LOGIC

        if(isset($_POST['create_employee'])){
            $stmt = $conn->prepare('INSERT INTO darbuotojai (name) VALUES (?)');
            $stmt->bind_param('s', $name);
            $name = $_POST['name'];
            
            $stmt->execute();
            $stmt->close();
            header("Location: /PHP-CRUD-Project-Manager/?path=darbuotojai");
            // header('Location: ' . $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            die;
        }

    // INSERT NEW PROJECT LOGIC

        if(isset($_POST['create_project'])){
            $stmt = $conn->prepare('INSERT INTO projektai (name) VALUES (?)');
            $stmt->bind_param('s', $name);
            $name = $_POST['name'];
            
            $stmt->execute();
            $stmt->close();
            header("Location: /PHP-CRUD-Project-Manager/?path=projektai");
            // header('Location: ' . $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            die;
        }
    
    $conn->close();
    ?>
    <br>
    <form action="" method="POST">
        <label for="name" style="font-size: 16px; color: grey" >Darbuotojas:</label><br>
        <input type="text" id="name" name="name" value="" placeholder="Įveskite naujo darbuotojo vardą"><br>
        <input type="submit" name="create_employee" value="Pridėti">
    </form>
    <br>
    <form action="" method="POST">
        <label for="name" style="font-size: 16px; color: grey">Projektas:</label><br>
        <input type="text" id="name" name="name" value="" placeholder="Įveskite naujo projekto pavadinimą"><br>
        <input type="submit" name="create_project" value="Pridėti">
    </form>
</main>
</body>
</html>
