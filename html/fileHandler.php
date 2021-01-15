<?php    
    session_start();
    require_once "config.php";

    $_SESSION['error'] = array();

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

    // Cek HTTP method harus POST
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        session_destroy();
        header("Location: http://$host$uri/index.php");
    }

    $target_dir = "/var/www/media/";
    $file_name = basename($_FILES['file']['name']);
    $file_name_noext = explode(".", $file_name);
    $file_name_ext = "";
    if (count($file_name_noext) <= 1) {
        array_push($_SESSION['error'], "Format file salah.");
        header("Location: http://$host$uri/index.php");
    } else {
        $file_name_ext = strtolower(array_pop($file_name_noext));
        $file_name_noext = implode(".", $file_name_noext);
    }

    $uploadOk = 1;

    // Cek input kelompok harus sesuai
    if (isset($_POST['kelompok'])) {
        $nama_kelompok = array("1", "2", "3", "4", "5", "6", "7");
        if (!in_array($_POST['kelompok'], $nama_kelompok)) {
            array_push($_SESSION['error'], "Nama kelompok tidak ada.").
            $uploadOk = 0;
        }
    } else {
        array_push($_SESSION['error'], "Nama kelompok tidak ada.");
        $uploadOk = 0;
    }

    // Cek mime file
    if (isset($_FILES['file'])) {
        $file_mime = mime_content_type($_FILES['file']["tmp_name"]);
        if (!isset($file_mime) || !in_array($file_mime, array("image/png", "image/jpeg", "image/gif"))) {
            array_push($_SESSION['error'], "Mime file harus berupa gambar.");
            $uploadOk = 0;
        }
    } else {
        array_push($_SESSION['error'], "File tidak ada.");
        $uploadOk = 0;
    }

    // Cek tipe file
    if ($file_name_ext != "jpg" && $file_name_ext != "png" && $file_name_ext != "jpeg" && $file_name_ext != "gif" ) {
        array_push($_SESSION['error'], "File harus berupa gambar");
        $uploadOk = 0;
    }

    // Cek ukuran
    if ($_FILES["file"]["size"] > 4000000) {
        array_push($_SESSION['error'], "Ukuran file terlalu besar. (Max 4MB)");
        $uploadOk = 0;
    }

    // Virus Total
    if ($uploadOk) {
        $scan_this = curl_file_create($_FILES['file']['tmp_name']);
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://www.virustotal.com/api/v3/files");
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array("x-apikey: ".$api_key));
        curl_setopt($ch1, CURLOPT_POST,1);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, array('file' => $scan_this));
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);

        $return1 = curl_exec($ch1);
        $return1 = json_decode($return1, 1);
        curl_close($ch1);

        $analyses_id = $return1["data"]["id"];

        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://www.virustotal.com/api/v3/analyses/".$analyses_id);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array("x-apikey: ".$api_key));
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

        $return2 = curl_exec($ch2);
        $return2 = json_decode($return2, 1);
        curl_close($ch2);

        $malicious = $return2["data"]["attributes"]["stats"]["malicious"];
    }

    if ($malicious > 0) {
        array_push($_SESSION['error'], "File memiliki virus / malware.");
        $uploadOk = 0;
    }

    // Ganti nama
    $file_name_noext = $_POST['kelompok'] . "_" . date("Y-m-d H:i:s") . "_" . $file_name_noext;
    $file_name_hash = md5($file_name_noext) . "." . $file_name_ext;
    $target_file = $target_dir . $file_name_hash;
    if (file_exists($target_file)) {
        array_push($_SESSION['error'], "File sudah ada.");
        $uploadOk = 0;
    }

    // Kirim data
    if ($uploadOk === 1) {
        if (move_uploaded_file($_FILES["file"]['tmp_name'], $target_file)) {
            try{
                $conn = new PDO("mysql:host=$hostdb;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("INSERT INTO `files` (kelompok, file) VALUES (:kelompok, :files)");
                
                $stmt->bindParam(':kelompok', $kelompok);
                $stmt->bindParam(':files', $file);
                $kelompok = $_POST['kelompok'];
                $file = $file_name_hash;
                $stmt->execute();
                $_SESSION['success'] = "File berhasil diupload";
            } catch (PDOException $e) {
                array_push($_SESSION['error'], "Terjadi kesalahan database.");
                unlink($target_file);
            }
        } else {
            array_push($_SESSION['error'], "Terjadi kesalahan.");
        }
    }

    header("Location: http://$host$uri/index.php");
?>