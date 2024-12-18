<?php

error_reporting(E_ALL);
session_start();

// Database connection
$sp = new mysqli("localhost", "root", "", "oas");
if ($sp->connect_errno) {
    die("Database connection failed: " . $sp->connect_error);
}

// Directories for uploads
$picpath = "studentpic/";
$docpath = "studentdoc/";
$proofpath = "studentproof/";

// Check if user is logged in
$id = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if (!$id) {
    die("User is not logged in.");
}

// Handle file upload
if (isset($_POST['fpicup'])) {
    // Define file paths
    $picpath = $picpath . basename($_FILES['fpic']['name']);
    $docpath1 = $docpath . basename($_FILES['ftndoc']['name']);
    $docpath2 = $docpath . basename($_FILES['ftcdoc']['name']);
    $docpath3 = $docpath . basename($_FILES['fdmdoc']['name']);
    $docpath4 = $docpath . basename($_FILES['fdcdoc']['name']);
    $proofpath1 = $proofpath . basename($_FILES['fide']['name']);
    $proofpath2 = $proofpath . basename($_FILES['fsig']['name']);

    // Ensure upload directories exist
    if (!is_dir("studentpic")) mkdir("studentpic", 0777, true);
    if (!is_dir("studentdoc")) mkdir("studentdoc", 0777, true);
    if (!is_dir("studentproof")) mkdir("studentproof", 0777, true);

    // Move uploaded files
    if (
        move_uploaded_file($_FILES['fpic']['tmp_name'], $picpath) &&
        move_uploaded_file($_FILES['ftndoc']['tmp_name'], $docpath1) &&
        move_uploaded_file($_FILES['ftcdoc']['tmp_name'], $docpath2) &&
        move_uploaded_file($_FILES['fdmdoc']['tmp_name'], $docpath3) &&
        move_uploaded_file($_FILES['fdcdoc']['tmp_name'], $docpath4) &&
        move_uploaded_file($_FILES['fide']['tmp_name'], $proofpath1) &&
        move_uploaded_file($_FILES['fsig']['tmp_name'], $proofpath2)
    ) {
        // File names for database
        $img = $_FILES['fpic']['name'];
        $img1 = $_FILES['ftndoc']['name'];
        $img2 = $_FILES['ftcdoc']['name'];
        $img3 = $_FILES['fdmdoc']['name'];
        $img4 = $_FILES['fdcdoc']['name'];
        $img5 = $_FILES['fide']['name'];
        $img6 = $_FILES['fsig']['name'];

        // Insert data into the database
        $query = "INSERT INTO t_userdoc (s_id, s_pic, s_tenmarkpic, s_tencerpic, s_twdmarkpic, s_twdcerpic, s_idprfpic, s_sigpic) 
                  VALUES ('$id', '$img', '$img1', '$img2', '$img3', '$img4', '$img5', '$img6')";

        if ($sp->query($query)) {
            echo "<script>alert('Document uploaded successfully');window.location='admsnreport.php';</script>";
        } else {
            echo "Error while saving data: " . $sp->error;
        }
    } else {
        echo "There is an error, please retry or check the upload path and permissions.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Documents</title>
    <link rel="stylesheet" href="css/admform.css">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/bootstrap-theme.min.css">
    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
</head>
<body style="background-image:url('./images/inbg.jpg');">
    <form id="docup" enctype="multipart/form-data" name="docup" action="documents.php" method="post">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <img src="images/cutm.jpg" width="100%" style="box-shadow: 1px 5px 14px #999999;">
                </div>
            </div>
        </div>
        <div class="container" style="margin-left:100px;">
            <table class="table able-striped" style="background-color:#fff!important;">
                <thead>
                    <tr>
                        <th>Upload Your Documents</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Passport Size Image:</td>
                        <td><input type="file" id="fpic" name="fpic" required></td>
                    </tr>
                    <tr>
                        <td>10th Mark Sheet:</td>
                        <td><input type="file" id="ftndoc" name="ftndoc" required></td>
                    </tr>
                    <tr>
                        <td>10th Certificate:</td>
                        <td><input type="file" id="ftcdoc" name="ftcdoc" required></td>
                    </tr>
                    <tr>
                        <td>12th/Diploma Mark Sheet:</td>
                        <td><input type="file" id="fdmdoc" name="fdmdoc" required></td>
                    </tr>
                    <tr>
                        <td>12th/Diploma Certificate:</td>
                        <td><input type="file" id="fdcdoc" name="fdcdoc" required></td>
                    </tr>
                    <tr>
                        <td>Identity Proof:</td>
                        <td><input type="file" id="fide" name="fide" required></td>
                    </tr>
                    <tr>
                        <td>Signature:</td>
                        <td><input type="file" id="fsig" name="fsig" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" id="fpicup" name="fpicup" class="btn btn-primary" value="Upload"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container">
            <center>
                <div class="jumbotron" style="width:100%; box-shadow: -3px 3px 10px #999999; margin-top:10px;">
                    <h3>Declaration By The Applicant</h3>
                    <p>I hereby solemnly declare that all the particulars given in this form are true to the best of my knowledge and belief. I shall abide by the rules and regulations laid down by the College from time to time. In case the particulars furnished by me are found false, my admission stands canceled.</p>
                    <input type="checkbox" id="dec" name="dec" value="I accept" required> I accept
                </div>
            </center>
        </div>
    </form>
</body>
</html>