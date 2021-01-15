<?php
    session_start();
    require_once 'config.php';
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    if (!isset($_GET['kelompok'])) {
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    </head>
    <body> 
 
        <div class="container my-4">
            <h1 class="text-center">File Viewer</h1>
        </div>

        <form action="#" class="container my-4" novalidate>

            <div class="form-row mb-4">
                <label for="kelompok">Kelompok</label>
                <select class="custom-select" onchange="location = this.value;" required>
                    <option></option>
                    <option value="viewer.php?kelompok=1">Kelompok 1</option>
                    <option value="viewer.php?kelompok=2">Kelompok 2</option>
                    <option value="viewer.php?kelompok=3">Kelompok 3</option>
                    <option value="viewer.php?kelompok=4">Kelompok 4</option>
                    <option value="viewer.php?kelompok=5">Kelompok 5</option>
                    <option value="viewer.php?kelompok=6">Kelompok 6</option>
                    <option value="viewer.php?kelompok=7">Kelompok 7</option>
                </select>
            </div>

            <div class="form-row mb-4">
                <div class="col-md-3 mb-4">
                    <a href="index.php" class="btn btn-info">File Upload</a>
                </div>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        
    </body>
</html>
<?php
    } else {
        if (!in_array($_GET['kelompok'], array("1", "2", "3", "4", "5", "6", "7"))) {
            header("Location: http://$host$uri/viewer.php");
        } else {
            try {
                $conn = new PDO("mysql:host=$hostdb;dbname=$dbname", $username, $password);
                $getFiles = $conn->prepare("SELECT file FROM files WHERE kelompok=:kelompok");
                $getFiles->bindParam(":kelompok", $kelompok);
                $kelompok = $_GET['kelompok'];
                $getFiles->execute();
                $files = $getFiles->fetchAll();
            } catch (PDOException $e) {
                array_push($_SESSION['error'], "Terjadi kesalahan database.");
                header("Location: http://$host$uri/index.php");
            }
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css" />
    </head>
    <body> 
 
        <div class="container my-4">
            <h1 class="text-center"><a href="viewer.php">File Viewer</a></h1>
            <div class="col-md-12">
                <div class="row">
                <hr>

                    <div class="gal">
                    <?php
                    foreach($files as $file) {
                        $f = $file['file'];
                    ?><img src="<?php echo "http://$host$uri:$port/$f"?>" /><?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        
    </body>
</html>

<?php 
        }
    };
?>