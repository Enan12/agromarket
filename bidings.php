<?php
require_once "utils.php";
require_once "dbconnect.php";

if(session_status()==PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['name'])) {
    header("Location:login.php");
    die;
}

class Bid {
    public $bid_id,$seller,$user_id, $prod_id, $prod_name, $base_price, $my_bid, $highest_bid, $time_left, $user_rating;
};
$bids=[];
$user_id = test_input($_SESSION['user_id']);

$selling_bid = 1;
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(isset($_POST['refresh_bid'])) {
        $id = test_input($_POST['refresh_bid']);
        $out = "০ টাকা";
        $sql = "SELECT MAX(total_price) FROM tbl_bids WHERE id=?;";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 1) {
                $row = $res->fetch_assoc();
                $out = $row['MAX(total_price)'];
            }
            if($out=='') die("বিড করা হয় নি");
            else die($out." টাকা");
        }
        else die("error");
    }
}
else if(isset($_GET['page_var'])) {
    if(test_input($_GET['page_var'])=="buying_bids") {
        $selling_bid = 0;
        $sql = "SELECT tbl_users.name as 'username', tbl_product.name as 'product_name', tbl_bids.id as 'bid_id', tbl_users.*, tbl_product.*, tbl_bids.*, tbl_rating.* FROM tbl_users 
INNER JOIN tbl_bids ON(tbl_users.id=tbl_bids.user_id) INNER JOIN tbl_product ON(tbl_bids.product_id=tbl_product.id) LEFT JOIN tbl_rating ON(tbl_product.user_id=tbl_rating.ratee_id)
WHERE tbl_bids.user_id=? ORDER BY tbl_product.deadline ASC;";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $tmp = new Bid();

                    $tmp->bid_id = $row['bid_id'];
                    $tmp->user_id = $row['user_id'];
                    $tmp->seller = $row['username'];
                    $tmp->prod_id = $row['product_id'];
                    $tmp->prod_name = $row['product_name'];
                    $tmp->my_bid = $row['total_price'];
                    $tmp->base_price = $row['total_baseprice'];
                    $tmp->time_left = $row['deadline'];
                    $tmp->user_rating = $row['rating'];

                    $bids[] = $tmp;
                }
            }
        }
    }
}
else {
    $sql = "SELECT tbl_users.name as 'username', tbl_product.name as 'product_name', tbl_bids.id as 'bid_id', tbl_users.*, tbl_product.*, tbl_bids.*, tbl_rating.* FROM tbl_users 
INNER JOIN tbl_bids ON(tbl_users.id=tbl_bids.user_id) INNER JOIN tbl_product ON(tbl_bids.product_id=tbl_product.id) INNER JOIN tbl_rating ON(tbl_users.id=tbl_rating.ratee_id)
WHERE tbl_product.user_id=? ORDER BY tbl_product.deadline ASC;";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $tmp = new Bid();

                $tmp->bid_id = $row['bid_id'];
                $tmp->user_id = $row['user_id'];
                $tmp->seller = $row['username'];
                $tmp->prod_id = $row['product_id'];
                $tmp->prod_name = $row['product_name'];
                $tmp->my_bid = $row['total_price'];
                $tmp->base_price = $row['total_baseprice'];
                $tmp->time_left = $row['deadline'];
                $tmp->user_rating = $row['rating'];

                $bids[] = $tmp;
            }
        }
    }
}


?>
<!DOCTYPE HTML>
<html>
<head>
	<?php require_once "head.php"; ?>
    <script src="datetime/build/jquery.datetimepicker.full.js"></script>
    <script type="text/javascript" src="rating/jquery.rateyo.min.js"></script>
    <script type="text/javascript" src="countdown/jquery.countdown.min.js"></script>
    <link rel="stylesheet" type="text/css" href="datetime/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="rating/jquery.rateyo.min.css"/>
</head>
<body onload="render_rating()">
	<?php require_once "navbar.php"; ?>
    <!-- Modalbid -->
    <div class="modal fade" id="myModalbid" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>বিড করুন</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form class="form-horizontal" method="post" action="products.php?action=bid">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">অবশিষ্ট সময়: </label>
                                <span id="clock2" class="modal-item"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">সর্বাধিক দাম: </label>
                                <h5 id="modal-highest-price" class="modal-item"></h5>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">নিজের দেওয়া দাম: </label>
                                <h5 id="modal-own-price" class="modal-item"></h5>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name"> নতুন দাম(টাকা): </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control product-input" name="price" id="name" placeholder=" আপনার প্রস্তাবিত দামটি লিখুন">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" name="submit" class="btn btn-danger">বিড কনফারম করুন</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modalbid end-->

    <div class="btn-group btn-group-justified">
        <a href="bidings.php?page_var=buying_bids" class="btn <?php echo ($selling_bid ? "btn-default" : "btn-primary"); ?>">পণ্য ক্রয়ের নিলাম</a>
        <a href="bidings.php" class="btn <?php echo (!$selling_bid ? "btn-default" : "btn-primary"); ?>">পণ্য বিক্রয়ের নিলাম</a>
    </div>
    <div class="content">
        <div class="project-section wow bounceIn animated" data-wow-delay="0.1s" style="visibility: visible; -webkit-animation-delay: 0.1s;">
            <div class="container">
                <table class="table">
                    <thead>
                    <tr>
                        <th>পণ্য</th>
                        <th>বিক্রেতা(বিশ্বস্ততা)</th><?php if(!$selling_bid) echo "
                        <th>রেটিং দিন</th>"; ?>
                        <th>বিড দেখুন</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!$selling_bid)
                        foreach ($bids as $x) {
                            echo "
                            <tr class=\"info\">
                                <td>$x->prod_name</td>
                                <td>$x->seller (<p style='display: inline-block' rating='$x->user_rating' class='rateYo-readonly'></p>)</td>
                                <td class='rateYo' userid='$x->user_id'></td>
                                <td><button id='modal-bid-btn' type=\"button\" class=\"btn btn-success\" data-ownprice='$x->my_bid' data-deadline='$x->time_left' data-bid-id=\"$x->bid_id\" data-toggle=\"modal\" data-target=\"#myModalbid\">আবার বিড করুন</button></td>
                            </tr>";
                        }
                    ?>
                    </tbody>
                </table>
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
                </div>
            </div>
            <div class="footer-middle wow fadeInDown Big animated animated" data-wow-delay="0.4s"></div>
            <div class="footer-bottom wow bounceInRight animated" data-wow-delay="0.4s" style="visibility: visible; -webkit-animation-delay: 0.4s;">
                <p> Copyright &copy;2015  All rights  Reserved <a href="http://w3layouts.com" target="target_blank"></a></p>
            </div>
            <a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
        </div>
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
                        function en2bn (d) {
                            var arr = "০১২৩৪৫৬৭৮৯";
                            var str = ""+d, res="";
                            for(var i=0; i<str.length; i++) {
                                res+= (str.charCodeAt(i)>=48 && str.charCodeAt(i)<=57  ? arr[str.charCodeAt(i)-48] : str[i]);
                            }
                            return res;
                        }
                        function render_rating() {
                            $(".rateYo-readonly").each(function () {
                                var id = $(this).attr("rating");
                                //rating
                                $(this).rateYo({
                                    rating: id,
                                    halfStar: true,
                                    readOnly: true,
                                    starWidth: "15px",
                                    multiColor: {
                                        "startColor": "#FF0000", //RED
                                        "endColor"  : "#00FF00"  //GREEN
                                    }
                                });
                            });
                            $(".rateYo").each(function () {
                                var id = $(this).attr("userid");
                                //rating
                                $(this).rateYo({
                                    rating: id,
                                    halfStar: true,
                                    starWidth: "30px",
                                    multiColor: {
                                        "startColor": "#FF0000", //RED
                                        "endColor"  : "#00FF00"  //GREEN
                                    }
                                });
                            });
                        }
                        //triggered when modal is about to be shown
                        $(document).on("click", "#modal-bid-btn", function (e) {
                            //get data-id attribute of the clicked element
                            var id = $(this).data('bid-id');
                            var deadline = $(this).data('deadline');
                            var ownprice = $(this).data('ownprice');

                            $('#clock2').countdown(deadline, function(event) {
                                var tmp = en2bn(event.strftime('%D দিন %H:%M:%S'));
                                $(this).html(tmp);
                            });
                            refresh_bid();

                            function refresh_bid() {
                                $.post('bidings.php', {refresh_bid: id}, function($data, $status) {

                                    if($data!="error") {
                                        $("#modal-highest-price").html($data);
                                    }
                                    else alert("রিফ্রেশ সম্ভব হচ্ছে না");
                                });
                                setTimeout(refresh_bid, 2000);
                            }
                        });
					</script>
				<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
				</div>
		   </div>

 </body>
</html> 