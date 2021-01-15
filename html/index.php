<?php
    session_start();
?>

<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    </head>
    <body>
        <div class="container my-4">
            <h1 class="text-center">Upload Image Server</h1>
        </div>
        <form method="POST" action="fileHandler.php" enctype="multipart/form-data" class="needs-validation container my-4" novalidate>

            <?php
                if (isset($_SESSION["success"])) {
                    ?>
                    <div class="alert alert-success fade show" id="alert-success" role="alert">
                        <?php echo $_SESSION["success"];?>
                        
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                };
            ?>

            <div class="alert alert-danger fade show <?php if(!isset($_SESSION['error']) || !count($_SESSION['error'])) echo "d-none";?>"  id="alert" role="alert">
                <span>
                    <?php
                        foreach($_SESSION['error'] as $error) {
                            echo $error." ";
                        }
                    ?>
                </span>
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="form-row mb-4">
                <label for="kelompok">Kelompok</label>
                <select class="custom-select" id="kelompok" name="kelompok" required>
                    <option value="1">Kelompok 1</option>
                    <option value="2">Kelompok 2</option>
                    <option value="3">Kelompok 3</option>
                    <option value="4">Kelompok 4</option>
                    <option value="5">Kelompok 5</option>
                    <option value="6">Kelompok 6</option>
                    <option value="7">Kelompok 7</option>
                </select>
            </div>

            <div class="form-row mb-4 custom-file">
                <input type="file" class="custom-file-input" id="file" name="file" required>
                <label class="custom-file-label" for="file" id="file-label">Pilih File...</label>
            </div>

            <div class="form-row mb-4">
                <div class="col-md-3 mb-4">
                    <button class="btn btn-success" id="submit" type="submit" name="submit" value="submit" disabled>Submit</button>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="viewer.php" class="btn btn-info">File Viewer</a>
                </div>
            </div>
        </form>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        
        <script src="/script.js"></script>
    </body>
</html>

<?php
session_destroy();
?>