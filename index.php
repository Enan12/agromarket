<?php
require_once "dbconnect.php";

function count_users($conn) {
    $user_count = 0;
    $sql = "SELECT COUNT(*) FROM tbl_users;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $user_count = (string)$row['COUNT(*)'];
        $user_count = str_pad($user_count, 6, "0", STR_PAD_LEFT);
    }
    return $user_count;
}

function count_visitors($conn) {
    $visit_count = 0;
    $sql = "SELECT SUM(visit_count) FROM tbl_visitor;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $visit_count = (string)$row['SUM(visit_count)'];
        $visit_count = str_pad($visit_count, 6, "0", STR_PAD_LEFT);
    }
    return $visit_count;
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<?php require_once "head.php"; ?>
</head>

<body>
<div class="header" id="home">
    <div class="container">
        <div class="header-top">
            <?php require_once "navbar_items.php"; ?>
            <!--search-->

            <!--<div class="search">
                <form>
                    <input type="text" value="সার্চ..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '';}" >
                    <input type="submit" value="">
                </form>
            </div>-->
            <div class="clearfix"></div>
        </div>
        <div class="logo wow bounceIn animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
            <a href="index.php">এগ্রোমার্কেট</a>
        </div>
        <br>
        <div style="text-align:right; ">
            <h2 style="color:white" ><b><?php echo count_visitors($conn); ?><br>বার ওয়েবসাইট ভিজিট করেছেন!</b></h2>
        </div>
        <br>
        <div style="text-align:right; ">
            <h2 style="color:white" ><b><?php echo count_users($conn); ?><br>জন রেজিস্টার্ড সদস্য!</b></h2>
        </div>
        <div class="header-bottom">
            <div class="header-grids">
                <div class="col-md-3 header-grid">
                    <div class="header-img1 wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
                        <img src="images/icon4.png"" class="img-responsive" alt="/">
                        <h4>রেটিং</h4>
                        <p>আপনার রিভিউ দিয়ে আমাদের ভালো করতে অনুপ্রানিত করুন</p>
                    </div>
                </div>
                <a href="about.php">
                    <div class="col-md-3 header-grid">
                        <div class="header-img2 wow fadeInDownBig animated animated" data-wow-delay="0.4s">
                            <img src="images/icon5.png"" class="img-responsive" alt="/">
                            <h4>আমাদের পরিচিতি</h4>
                            <p>কেন আমাদের এই উদ্যোগ ? কি উপকার হতে পারে? </p>
                        </div>
                    </div>
                </a>
                <a href="products.php">
                    <div class="col-md-3 header-grid">
                        <div class="header-img3 wow fadeInUpBig animated animated" data-wow-delay="0.4s">
                            <img src="images/icon6.png"" class="img-responsive" alt="/">
                            <h4>আমার কার্ট</h4>
                            <p>যা যা ক্রয় করতে চান তা এখানে সংগ্রহ করুন </p>
                        </div>
                    </div>
                </a>
                <a href="register.php">
                    <div class="col-md-3 header-grid">
                        <div class="header-img4 wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
                            <img src="images/icon7.png"" class="img-responsive" alt="/">
                            <h4>নতুন সদস্য? </h4>
                            <p>এখানে রেজিস্টার করুন</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <br><br>
</div>
<div class="content">
    <div class="about-section">
        <div class="container">
            <div class="about-grids">
                <div class="col-md-5 about-img wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;"></div>
				<div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="service-section">
        <div class="container">
            <h3>আমাদের সেবাসমূহ</h3>
            <div class="service-grids">
                <div class="col-md-4 service-grid wow bounceInLeft animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
                    <img src="images/icon1.png" class="img-responsive" alt="/">
					<h4>বিডিং সিস্টেম</h4>
                </div>
                <div class="col-md-4 service-grid wow fadeInUpBig animated animated" data-wow-delay="0.4s">
					<img src="images/icon2.png" class="img-responsive" alt="/">
                    <h4>প্রোডাক্ট আপডেট</h4>
                </div>
                <div class="col-md-4 service-grid wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
                    <img src="images/icon3.png" class="img-responsive" alt="/">
                    <h4>দরকারি প্রোডাক্ট এর জন্য অনুরোধ</h4>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="work-section wow bounceIn animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
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
                        <div class="up1"></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-md-4 cat wow bounceIn animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;"></div>
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
            <div class="footer-middle wow fadeInDown Big animated animated" data-wow-delay="0.4s"></div>
        </div>
        <div class="footer-bottom wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
            <p>   Copyright &copy;2015  All rights  Reserved <a href="http://w3layouts.com" target="target_blank"></a></p>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $().UItoTop({ easingType: 'easeOutQuart' });
            });
        </script>
        <a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
    </div>
</div>
</body>
</html>
