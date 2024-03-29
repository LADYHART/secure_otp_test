<?php 
ob_start();
session_start();
include("include/connection.php");

@$mobile=$_POST['mobile'];
@$email=$_POST['email'];
@$password=$_POST['password'];
@$rcode=$_POST['rcode'];
@$acceptTC=$_POST['remember'];
@$action=$_POST['action'];
$otpmobile=@$_SESSION["signup_mobilematched"];

if($action == "register") {
    // Check if the mobile number matches the session variable
    if($otpmobile == $mobile) {
        $chkuser = mysqli_query($con,"SELECT * FROM `tbl_user` WHERE `mobile`='".$mobile."'");
        $userRow = mysqli_num_rows($chkuser);
        
        if($userRow == 0) {
            // User doesn't exist, proceed with registration
            $chkrcode = mysqli_query($con,"SELECT * FROM `tbl_user` WHERE `owncode`='".$rcode."'");
            $codeRow = mysqli_num_rows($chkrcode);
            
            if($codeRow != 0) {
                // Reference code exists, continue with registration
                $sql = mysqli_query($con,"INSERT INTO `tbl_user` (`mobile`, `email`, `password`,`code`,`owncode`,`privacy`,`status`) VALUES ('".$mobile."','".$email."','".md5($password)."','".$rcode."','','".$acceptTC."','1')");
                $userid = mysqli_insert_id($con);
                $refcode = $userid.refcode();
                $sql = mysqli_query($con,"UPDATE `tbl_user` SET `owncode` = '$refcode' WHERE `id`= '".$userid."'");
                $sql2 = mysqli_query($con,"INSERT INTO `tbl_wallet`(`userid`,`amount`,`envelopestatus`) VALUES ('".$userid."','20','0')");
                $sql3 = mysqli_query($con,"INSERT INTO `tbl_bonus`(`userid`,`amount`,`level1`,`level2`) VALUES ('".$userid."','0','0','0')");

                if($sql) {
                    unset($_SESSION["signup_mobilematched"]);
                    echo "1"; // Successful registration
                } else {
                    echo "0"; // Registration failed
                }
            } else {
                echo "3"; // Reference code doesn't exist
            }
        } else {
            echo "2"; // User already exists
        }
    } else {
        echo "4"; // Mobile number doesn't match session
    }
}
?>