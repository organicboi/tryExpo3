<?php
// include config file
include_once 'config.php';

// if signup button clicked
if (isset($_POST['signup'])) {
    if (
        empty($_POST['fname']) ||
        empty($_POST['lname']) ||
        empty($_POST['collegeId']) ||
        empty($_POST['password']) ||
        empty($_POST['confirmPassword']) ||
        empty($_FILES['image'])
    ) {
        echo 'All inputs are required';
    } else {
        // first name
        $firstName = mysqli_real_escape_string($conn, $_POST['fname']);
        // last name
        $lastName = mysqli_real_escape_string($conn, $_POST['lname']);
        // collegeid
        $collegeId = mysqli_real_escape_string($conn, $_POST['collegeId']);

        // collegeId validation
        $collegeIdQuery = "SELECT * FROM `users` WHERE collegeId = '{$collegeId}'";
        $runcollegeIdQuery = mysqli_query($conn, $collegeIdQuery);

        if ($runcollegeIdQuery) {
            if (mysqli_num_rows($runcollegeIdQuery) > 0) {
                echo 'collegeId is already exist.';
            } else {
                // check password length
                if (
                    strlen($_POST['password']) < 8 ||
                    strlen($_POST['confirmPassword']) < 8
                ) {
                    echo 'Please use 8 or more characters in password';
                } elseif ($_POST['password'] != $_POST['confirmPassword']) {
                    // match password
                    echo 'Password Not Matched';
                } else {
                    // store password
                    $password = md5($_POST['password']);

                    // profile image
                    $image = $_FILES['image'];

                    // image name
                    // size
                    // temporary name
                    // type

                    $imageName = $image['name'];
                    $imageSize = $image['size'];
                    $imageTempName = $image['tmp_name'];
                    $imageType = $image['type'];

                    // get image extension or type
                    $explode = explode('.', $imageName);
                    $lowercase = strtolower(end($explode));

                    // image extension required
                    $extension = ['png', 'jpg', 'jpeg'];

                    // if extension not matched
                    if (in_array($lowercase, $extension) == false) {
                        echo 'This Extension file not allowed,Please choose JPG or PNG.';
                    } else {
                        // random number
                        $random = rand(999999999, 111111111);
                        $time = time();
                        // image unique name
                        $uniqueImageName = $random . $time . $imageName;

                        // save image
                        move_uploaded_file(
                            $imageTempName,
                            '../images/' . $uniqueImageName
                        );

                        // user status
                        $status = 'Offline';

                        $insertQuery = "INSERT INTO `users` (firstName,lastName,collegeId,password,image,status)
                    VALUES('{$firstName}','{$lastName}','{$collegeId}','{$password}','{$uniqueImageName}','{$status}')";

                        $runInsertQuery = mysqli_query($conn, $insertQuery);
                        if (!$runInsertQuery) {
                            echo 'Failed while entering data in database';
                        } else {
                            echo 'success';
                        }
                    }
                }
            }
        }
    }
}
