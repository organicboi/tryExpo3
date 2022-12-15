<?php
// include config file
include_once 'config.php';
session_start();

// if "login" button clicked
if (isset($_POST['login'])) {
    // store email
    $collegeId = mysqli_real_escape_string($conn, $_POST['collegeId']);
    // store password
    $password = md5($_POST['password']);

    // check collegeId is unique or not
    $collegeIdQuery = "SELECT * FROM `users` WHERE collegeId = '$collegeId'";
    $runcollegeIdQuery = mysqli_query($conn, $collegeIdQuery);

    if (!$runcollegeIdQuery) {
        echo 'Query Failed';
    } else {
        if (mysqli_num_rows($runcollegeIdQuery) > 0) {
            $passwordQuery = "SELECT * FROM `users` WHERE collegeId = '$collegeId' AND password = '$password'";
            $runPasswordQuery = mysqli_query($conn, $passwordQuery);

            if (!$runPasswordQuery) {
                echo 'Query Failed';
            } else {
                if (mysqli_num_rows($runPasswordQuery) > 0) {
                    $fetchData = mysqli_fetch_assoc($runPasswordQuery);
                    $_SESSION['id'] = $fetchData['id'];
                    // update status
                    $status = 'Online';

                    // status query
                    $statusQuery = "UPDATE users SET status = '{$status}' WHERE id = '{$_SESSION['id']}'";
                    $runStatusQuery = mysqli_query($conn, $statusQuery);
                    if (!$runStatusQuery) {
                        echo 'failed while updating status';
                    } else {
                        echo 'success';
                    }
                } else {
                    echo 'Password not matched';
                }
            }
        } else {
            echo 'Invalid collegeId address';
        }
    }
}
?>
