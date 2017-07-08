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

if(session_status()==PHP_SESSION_NONE){
	session_start();
}
$referrer = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php');

if (isset($_GET['usage'])) {
    if (test_input($_GET['usage']) == 'login') {
        if (isset($_GET['feedback']) && test_input($_GET['feedback']) == 'failed') {
            echo "<script>alert(\"Incorrect email or password\")</script>";
        }
		else if (isset($_POST["uemail"]) && isset($_POST["pass1"])) {
            $mail = trim(test_input($_POST["uemail"]));
            $pass = trim(test_input($_POST["pass1"]));
			$pass = $salt . $pass . $salt;

            $sql = "SELECT * FROM tbl_users WHERE email=? and pass=MD5(?);";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $mail, $pass);
                $stmt->execute();
                $res = $stmt->get_result();

                //die($mail." ".$type.$stmt->num_rows);
                //$res = $conn->query($sql);
                if ($res->num_rows == 1) {
                    $row = $res->fetch_assoc();

                    $_SESSION['name'] = $row['name'];
					$_SESSION['user_id'] = $row['id'];
                    $_SESSION['mail'] = $mail;
					$_SESSION['created'] = time();

					if(isset($_GET['referrer'])) $referrer = test_input($_GET['referrer']);
                    if (strpos($referrer, $_SERVER['HTTP_HOST']) !== false && $referrer!='login.php')
                        header("Location:" . $referrer);
                    else
                        header("Location:index.php");
                }
				else {
                    header("Location:login.php?usage=login&feedback=failed");
                }
            } else echo($conn->error);
        }
    }
    else if (test_input($_GET['usage']) == 'logout') {
        session_destroy();
        if (strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
            header("Location:" . $_SERVER['HTTP_REFERER']);
        else
            header("Location:index.php");
    }
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

			<form action="login.php?usage=login&referrer=<?php echo $referrer; ?>" method="post">
			<div class="contact">


					<div class="contact-head text-center">
					</div>

						<div class="contact-form wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
							<form action="login.php?usage=login" method="post">
								<div class="form-group">
							   <h3>যেভাবে লগ ইন করবেন:</h3>
							   <br>
							   </div>

								<div class="form-group">
								    <label for="email">ই-মেইলঃ</label>
								    <input type="email" name="uemail" class="form-control" id="uemail">
								</div>
								  <div class="form-group">
								    <label for="pwd">পাসওয়ার্ডঃ</label>
								    <input type="password" name="pass1" class="form-control" id="pass">
								  </div>
								  
								<button type="submit" class="btn btn-success">লগ ইন</button>
								<a class="btn btn-info" href="register.php">নতুন অ্যাকাউন্ট খুলুন</a>
							</form>
						</div>
						<!----- contact-form ------>
					</div>
					<!----- contact-grids ----->
			</div>
		</div>
	</div>




		   <div class="categories-section">
		   <div class="container">
		   <div class="footer-grids">
			<div class="col-md-4 up wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
	  <h3>আসন্ন উৎসবসমূহ</h3>
	  <div class="up1">
	 <div class="up-img">
	 <img src="images/im1.jpg">
	</div>
     <div class="up-text">
		 <a href="#">আম উৎসব ২০১৭</a>

		 </div>
		 <div class="clearfix"></div>
         </div>
		  <div class="up1">
	 <div class="up-img">
	 <img src="images/im2.jpg">
	</div>
     <div class="up-text">
		 <a href="#">ফলের সমারোহ</a>

		 </div>
		 <div class="clearfix"></div>
         </div>
		  <div class="up1">

	</div>

		 <div class="clearfix"></div>
         </div>
		 </div>
		 <div class="col-md-4 cat wow bounceIn animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">

	</div>
		 <div class="col-md-4 cont wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
		 <h3>যোগাযোগ</h3>
		 <ul>
		<li><i class="phone"></i></li>
		<li><p>+880-1629-561-508</p>
		<p>+880-1717-987-150</p></li>
		</ul>
		<ul>
	   <li><i class="smartphone"></i></li>
		<li><p>Osmany Hall,</p>
		<p>MIST</p></li>
		</ul>
		<ul>
		<li><i class="message"></i></li>
		<li><a href="mailto:mehreenhoque@mail.com">mehreenhoque@gmail.com</a>
		</ul>
		</div>
		 <div class="clearfix"></div>
		  </div>
		   </div>
		   <div class="footer-section">
		   <div class="container">
		   <div class="footer-top">
		 <div class="social-icons wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
		<a href="#"><i class="icon1"></i></a>
		<a href="#"><i class="icon2"></i></a>
		<a href="#"><i class="icon3"></i></a>
		<a href="#"><i class="icon4"></i></a>
		</div>
		</div>
		 <div class="footer-middle wow fadeInDown Big animated animated" data-wow-delay="0.4s">

		</div>
		<div class="footer-bottom wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
									<p> Copyright &copy;2015  All rights  Reserved <a href="http://w3layouts.com" target="target_blank"></a></p>
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
				</div>
		   </div>

 </body>
</html>
