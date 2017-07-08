<?php
/**
 * Created by PhpStorm.
 * User: avash
 * Date: 4/2/16
 * Time: 2:57 AM
 * php5-mysqlnd required
 */
require_once "utils.php";
require_once "dbconnect.php";
require 'phpmailer/PHPMailerAutoload.php';

$mailFrom = "agromkt13@gmail.com";
$mailFromPass = "avashavash";

if(session_status()==PHP_SESSION_NONE){
    session_start();
}
if (isset($_GET['usage'])) {
    if (test_input($_GET['usage']) == 'register') {
        if (isset($_GET['feedback']) && test_input($_GET['feedback']) == 'missmatch') {
            echo "<script>alert(\"পাসওয়ার্ড, মেলেনি, আবার চেষ্টা করুন\")</script>";
        }
        if (isset($_GET['feedback']) && test_input($_GET['feedback']) == 'success') {
            echo "<script>alert(\"রেজিস্ট্রেশান সম্পন্ন করতে ইমেইল চেক করুন\")</script>";
        }
        else {
            $name = test_input($_POST['name']);
            $pass = test_input($_POST['pwd']);
            $cpass = test_input($_POST['cpwd']);
            $phn = test_input($_POST['phn']);
            $mail = test_input($_POST['email']);
            if($pass!=$cpass) header("Location:register.php?usage=register&feedback=missmatch");
            $pass = $salt . $pass . $salt;
            $verification = generateRandomString(32);

            $sql = "INSERT INTO tbl_users (name,pass,email,phone,verification) VALUES (?,MD5(?),?,?,?);";
            if ($stmt = $conn->prepare($sql)) {//die($_SESSION['user_id']);
                $stmt->bind_param("sssss",$name,$pass,$mail,$phn,$verification);
                $stmt->execute();

                $hashed = $verification . $mail . $verification;
                $link = "http://" . $_SERVER['SERVER_NAME'] . "/agromarket/register.php?verify=" . $verification . "&id=" . md5($hashed);
                $email = $mail;

                $mail = new PHPMailer(); // create a new object
                $mail->IsSMTP(); // enable SMTP
                $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
                $mail->SMTPAuth = true; // authentication enabled
                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465; // or 587
                $mail->IsHTML(true);
                $mail->Username = $mailFrom;
                $mail->Password = $mailFromPass;
                $mail->SetFrom($mailFrom);
                $mail->Subject = "Agromarket Account Confirmation" ;
                $mail->Body = "স্বাগতম,<br> আপনার অ্যাকাউন্ট তৈরি হয়ে গিয়েছে। অ্যাকাউন্ট নিশ্চিত করতে এই লিংক এ ক্লিক করুনঃ<br>$link<br>ধন্যবাদ";
                $mail->AddAddress($email);
                $mail->SMTPDebug = false;
                $mail->do_debug = 0;

                if(!$mail->Send()) {
                    die("Mailer Error: " . $mail->ErrorInfo);
                }
                header("Location:register.php?usage=register&feedback=success");
            }
        }
    }
}
else if(isset($_GET['verify'])) {
    $ver = test_input($_GET['verify']);
    $id = test_input($_GET['id']);

    $sql = "SELECT * FROM tbl_users WHERE verification=? AND MD5(concat(?,email,?))=?;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $ver, $ver, $ver, $id);
        $stmt->execute();
        $res = $stmt->get_result();

        //die($mail." ".$type.$stmt->num_rows);
        //$res = $conn->query($sql);
        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $id = $row['id'];

            $sql = "UPDATE tbl_users SET status='APPROVED' WHERE id = ?;";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $id);
                $stmt->execute();
            }
            header("Location:login.php");
        }
        else {
            die("ভুল তথ্য");
        }
    } else echo($conn->error);
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<?php require_once "head.php"; ?>
</head>
<body>
<?php require_once "navbar.php"; ?>
<div class="maine">
	<div class="container">
		<div class="contact">
			<div class="contact-form wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<form action="register.php?usage=register" method="post">
					<div class="form-group" >
						<label for="name">নামঃ</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>
					<div class="form-group" >
						<label for="phoneno">ফোন নম্বরঃ</label>
						<input type="text" required class="form-control" id="phoneno" name="phn">
					</div>
					<div class="form-group">
						<label for="email">ইমেইলঃ</label>
						<input type="email" name='email' class="form-control" id="email" required>
					</div>
					<div class="form-group">
						<label for="pwd">পাসওয়ার্ডঃ</label>
						<input type="password" class="form-control" id="pwd" name="pwd" required>
					</div>
					<div class="form-group">
						<label for="pwd"> কনফার্ম পাসওয়ার্ডঃ</label>
						<input type="password" class="form-control" id="pwd" name="cpwd" required>
					</div>
					<input type="submit" class="btn btn-success" value="রেজিস্টার">
				</form>
			</div>
			<!----- contact-form ------>
		</div>
		<!----- contact-grids ----->
	</div>	
</div>

<div class="footer-section">
	<div class="container">
		<div class="footer-top">
			<div class="social-icons wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
				<a href="#"><i class="icon1"></i></a>
				<a href="#"><i class="icon2"></i></a>
				<a href="#"><i class="icon3"></i></a>
			</div>
		</div>
		<div class="footer-middle wow fadeInDown Big animated animated" data-wow-delay="0.4s"></div>
		<div class="footer-bottom wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
			<p> Copyright &copy;2015  All rights  Reserved <a href="http://w3layouts.com" target="target_blank"></a></p>
		</div>
		<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
	</div>
</div>
					<script type="text/javascript">
						$(document).ready(function() {
							/*
							var defaults = {
					  			containerID: 'toTop', // fading element id
								containerHoverID: 'toTopHover', // fading element hover id
								scrollSpeed: 1200,
								easingType: 'linear' 
					 		};
							*/
							
							$().UItoTop({ easingType: 'easeOutQuart' });
							
						});
					</script>
				<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>

 </body>
</html> 