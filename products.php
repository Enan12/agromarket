<?php
require_once "utils.php";
require_once "dbconnect.php";
require_once "bulletproof-master/src/bulletproof.php";

if(session_status()==PHP_SESSION_NONE){
    session_start();
}

class Product {
    public $product_id,$name,$location,$deadline,$amount,$unit,$baseprice,$total_baseprice,$user_id,$user_name,$rating,$image,$description,$category;
};
$products = [];

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(isset($_POST['search'])) {

    }
    else if(isset($_POST['refresh_bid'])) {
        $id = test_input($_POST['refresh_bid']);
        $out = "০ টাকা";
        $sql = "SELECT MAX(total_price) FROM tbl_bids WHERE product_id=?;";
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
    else if(isset($_POST['offset']) && isset($_POST['limit']) && isset($_POST['category'])){
        if(test_input($_POST['category'])=='own' && isset($_SESSION["name"])) {
            $sql = "SELECT tbl_users.name as 'username', tbl_users.*, tbl_product.*, tbl_rating.* FROM tbl_product JOIN tbl_users ON (tbl_product.user_id=tbl_users.id) JOIN tbl_rating ON(tbl_users.id=tbl_rating.ratee_id) WHERE user_id = ? ORDER BY ? LIMIT ? OFFSET ?;";
            if ($stmt = $conn->prepare($sql)) {
                $order_by = (isset($_POST['order_by']) ? test_input($_POST['order_by']) : 'id');
                $limit = test_input($_POST['limit']);
                $offset = test_input($_POST['offset']);
                $user_id = $_SESSION['user_id'];

                $stmt->bind_param("ssss", $user_id, $order_by, $limit, $offset);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        $tmp = new Product();

                        $tmp->product_id = $row['id'];
                        $tmp->name = $row['name'];
                        $tmp->location = $row['location'];
                        $tmp->deadline = $row['deadline'];
                        $tmp->amount = $row['amount'];
                        $tmp->unit = $row['unit'];
                        $tmp->baseprice = $row['baseprice'];
                        $tmp->total_baseprice = $row['total_baseprice'];
                        $tmp->user_id = $row['user_id'];
                        $tmp->user_name = $row['username'];
                        $tmp->rating = $row['rating'];
                        $tmp->image = $row['image'];
                        $tmp->category = $row['category'];
                        $tmp->description = $row['description'];

                        $products[] = $tmp;
                    }
                }
            }
        }
        else {
            $sql = "SELECT tbl_users.name as 'username', tbl_users.*, tbl_product.*,tbl_rating.* FROM tbl_product JOIN tbl_users ON (tbl_product.user_id=tbl_users.id) JOIN tbl_rating ON(tbl_users.id=tbl_rating.ratee_id) WHERE category LIKE ? ORDER BY ? LIMIT ? OFFSET ?;";
            if ($stmt = $conn->prepare($sql)) {
                $order_by = (isset($_POST['order_by']) ? test_input($_POST['order_by']) : 'id');
                $temp = test_input($_POST['category']);
                $limit = test_input($_POST['limit']);
                $offset = test_input($_POST['offset']);

                $stmt->bind_param("ssss", $temp, $order_by, $limit, $offset);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        $tmp = new Product();

                        $tmp->product_id = $row['id'];
                        $tmp->name = $row['name'];
                        $tmp->location = $row['location'];
                        $tmp->deadline = $row['deadline'];
                        $tmp->amount = $row['amount'];
                        $tmp->unit = $row['unit'];
                        $tmp->baseprice = $row['baseprice'];
                        $tmp->total_baseprice = $row['total_baseprice'];
                        $tmp->user_id = $row['user_id'];
                        $tmp->user_name = $row['username'];
                        $tmp->rating = $row['rating'];
                        $tmp->image = $row['image'];
                        $tmp->category = $row['category'];
                        $tmp->description = $row['description'];

                        $products[] = $tmp;
                    }
                }
            }
        }
    }
    if(count($products)==0) die('no_data');
    else die(json_encode($products));
}
else if(isset($_GET['cat']) && test_input($_GET['cat'])=='own' && !isset($_SESSION["name"])) {
    header("Location:login.php");
}
else if(isset($_GET['action'])) {
    if(test_input($_GET['action'])=="upload_success") {
        echo "<script>alert('নতুন পণ্য আপলোড হয়েছে');</script>";
    }
    else if(test_input($_GET['action'])=="upload_product" && isset($_SESSION["name"])) {
        $image = new Bulletproof\Image($_FILES);

        $image_filename = generateRandomString(64);
        $name = test_input($_POST['name']);
        $location = test_input($_POST['location']);
        $deadline = test_input($_POST['deadline']);
        $amount = test_input($_POST['amount']);
        $unit = test_input($_POST['unit']);
        $baseprice = test_input($_POST['baseprice']);
        $total_baseprice = test_input($_POST['total_baseprice']);
        $user_id = $_SESSION['user_id'];
        $description = test_input($_POST['description']);
        $category = test_input($_POST['category']);

        if($name!='' && $location!='' && $deadline!='' && $amount!='' && $unit!='' && $baseprice!='' && $image['image'])
        {
            $image->setName($image_filename);
            $image->setLocation('images/products');
            $image->setMime(array('jpeg', 'jpg', 'png', 'PNG', 'JPG', 'JPEG'));

            $upload = $image->upload();//var_dump($image);
            if($upload){
                //echo $upload->getFullPath(); // uploads/cat.gif
                $image_filename .= '.' . $image->getMime();
                $sql = "INSERT INTO tbl_product (name, location, deadline, amount, unit, baseprice, total_baseprice, user_id, image, description, category) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
                if ($stmt = $conn->prepare($sql)) {//die($_SESSION['user_id']);
                    $stmt->bind_param("sssssssssss",$name,$location,$deadline,$amount,$unit,$baseprice,$total_baseprice,$user_id,$image_filename,$description,$category);
                    $stmt->execute();
                    header("Location:products.php?action=upload_success");
                }
            }
            else{
                echo "<script>alert(" + $image["error"] + ");</script>";
            }
        }
        else {
            echo "<script>alert('ভুল ধরা পরেছে। ইনপুট চেক করুন');</script>";
        }
    }
    else if(test_input($_GET['action'])=="bid" && isset($_POST['price'])) {
        if(!isset($_SESSION['name'])) {
            header("Location:login.php");
            die;
        }
        $price = test_input($_POST['price']);
        $prod_id = test_input($_POST['prod_id']);
        $user = $_SESSION['user_id'];

        $sql = "INSERT INTO tbl_bids (user_id,product_id, total_price) VALUES(?,?,?);";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss",$user,$prod_id,$price);
            $stmt->execute();
            header("Location:bidings.php?page_var=buying_bids");
        }
        else echo "<script>alert('বিড করা যায় নি');</script>";
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
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #414142;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            padding-left: 3%;
            padding-right: 3%;
        }

        .sidenav a {
            text-decoration: none;
            font-size: 150%;
            color: white;
            display: block;
            transition: 0.3s;
            text-align: left;
            padding-left: 5%;
        }

        .sidenav a:hover, .offcanvas a:focus{
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }
    </style>
</head>
<body>
    <?php require_once "navbar.php";?><br>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>পণ্যর বিস্তারিত</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-7 col-xs-12">
                                <img class="img-responsive img-rounded modal-img" src="images/p10.jpg" alt="image" />
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <h3><strong id="modal-product-name" class="modal-item"></strong></h3>
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <h5><strong id="modal-seller" class="modal-item"></strong></h5>
                            </div>
                            <div class="col-sm-1 col-xs-3">
                                <h5>রেটিং:</h5>
                            </div>
                            <div class="col-sm-4 col-xs-9">
                                <div id="rateYo"></div>
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <h5 id="modal-amount" class="modal-item">পরিমাণ:</h5>
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <h5 id="modal-price" class="modal-item">দাম:</h5>
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <h5 id="modal-total-price" class="modal-item">দাম:</h5>
                            </div>
                            <div class="col-sm-5 col-xs-5">
                                <h5 class="modal-item">অবশিষ্ট সময়:</h5>
                            </div>
                            <div class="col-sm-3 col-xs-3">
                                <span id="clock" class="modal-item"></span>
                            </div>
                            <hr>
                            <div class="col-sm-5 col-xs-12">
                                <button type="button" id="modal-bid-btn" data-product-id="#" data-toggle="modal"
                                        data-dismiss="modal" data-target="#myModalbid" class="btn btn-primary"
                                    <?php if(!$login_done) echo "onclick=\"location.href='login.php'\""; ?>>বিড করুন</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <h5 id="modal-category" class="modal-item">পণ্যের ধরণ:</h5>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <h5 id="modal-location" class="modal-item">উৎপাদনস্থল:</h5>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <h5>পণ্যের বিবরণ:</h5>
                                <q><h5 id="modal-description" class="modal-item"></h5></q>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal end-->
    <!-- Modal2 -->
    <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><strong>পন্যের তথ্য</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form class="form-horizontal" id="add-product-form" enctype="multipart/form-data" method="POST" action="products.php?action=upload_product">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="name">পণ্যের নাম:</label>
                                <div class="col-sm-10">
                                    <input  type="text" class="form-control product-input" id="name" name="name" placeholder="পণ্যের নাম লিখুন">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="category">পণ্যের ধরণ:</label>
                                <div class="col-sm-10">
                                    <select name="category" class="form-control product-input">
                                        <option value="notselected">(ধরণ)</option>
                                        <option value="শস্য ">শস্য </option>
                                        <option value="শাঁক ">শাঁক </option>
                                        <option value="সবজি">সবজি</option>
                                        <option value="ফল">ফল</option>
                                        <option value="মসলা ">মসলা </option>
                                        <option value="মাছ">মাছ</option>
                                        <option value="অন্যান্য ">অন্যান্য </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="location">উৎপাদনস্থল:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control product-input" id="location" name="location" placeholder="পণ্য যে জায়গার">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="deadline">বিক্রির শেষ তারিখ:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control product-input" value="" name="deadline" id="datetimepicker_mask" placeholder="এখানে ক্লিক করুন">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="amount">পরিমাণ:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control product-input" id="amount" name="amount" placeholder="পণ্যের পরিমাণ">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="unit">একক:</label>
                                <div class="col-sm-10">
                                    <select  name="unit" class="form-control product-input">
                                        <option value="notselected">(একক)</option>
                                        <option value="কেজি">কেজি</option>
                                        <option value="পিস">পিস</option>
                                        <option value="ডজন">ডজন</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="baseprice">প্রতি এককের সর্বনিম্ন মূল্য:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control product-input" id="baseprice" name="baseprice" placeholder="পণ্যের মূল্য (টাকা)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="total_baseprice">মোট সর্বনিম্ন মূল্য:</label>
                                <div class="col-sm-10">
                                    <input  type="text" class="form-control product-input" id="total_baseprice" name="total_baseprice" placeholder="পণ্যের মূল্য (টাকা)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="description">প্রতি সংক্ষিপ্ত বর্ণনা:</label>
                                <div class="col-sm-10">
                                    <textarea type="text" form="add-product-form" class="form-control product-input" rows="5" id="description" name="description" placeholder="পণ্যের বর্ণনা(৫০০০ বর্ণ)"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="image">ছবি:</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
                                    <input type="file" accept="image/*" id="imgInp" name="image" capture="camera" style="max-width: inherit; overflow: hidden;">
                                </div>
                            </div>
                            <div class="form-group preview">
                                <div class="col-sm-10" style="float: right">
                                    <img id="preview" src="images/placeholder-camera-green.png" alt="preview" height="150" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="আপলোড" class="btn btn-success">
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
    <!--Modal2 end-->
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
                                <label class="control-label col-sm-2" for="name">দাম(টাকা): </label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="prod_id" id="modal-prod-id" value="">
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

    <a style="float:left;margin-left:1%;" href="#" class="btn-lg btn-success" onclick="openNav()">&#9776; পণ্যের ধরণ</a>
    <button  style="margin-left: 5%" id="own-btn" class="btn-lg btn-success" <?php if($login_done) echo "onclick=\"location.href='products.php?cat=own'\""; else echo "onclick=\"location.href='login.php'\""; ?> >আমার সব পণ্য </button>
    <button  data-toggle="modal" data-target="#myModal2" <?php if(!$login_done) echo "onclick=\"location.href='login.php'\""; ?> class="btn-lg btn-danger"><span class="glyphicon glyphicon-plus"></span>নতুন পণ্য </button>

    <div class="content">
        <div class="project-section wow bounceIn animated" data-wow-delay="0.1s" style="visibility: visible; -webkit-animation-delay: 0.1s;">
            <div class="container" style="padding-left: 6%">
                <div class="port-grids">
                    <div class="port1">
                        <div class="dynamic-load"></div>
                        <div class="holder"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <button class="btn-lg btn-info" id="view_more" style="margin: auto; display: block;">আরো দেখুন</button>
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
    <script type="text/javascript">
        var items_loaded = 0;
        var limit = 1;
        var cat = '<?php echo (isset($_GET['cat']) ? test_input($_GET['cat']) : "%"); ?>';
        var order_by = '';
        var all_products=[];

        function en2bn (d) {
            var arr = "০১২৩৪৫৬৭৮৯";
            var str = ""+d, res="";
            for(var i=0; i<str.length; i++) {
                res+= (str.charCodeAt(i)>=48 && str.charCodeAt(i)<=57  ? arr[str.charCodeAt(i)-48] : str[i]);
            }
            return res;
        }
        function bn2en (d) {
            var str='';
            for(var i=0; i<d.length; i++) {
                switch (d[i]) {
                    case '০':
                        str+='0';
                        break;
                    case '১':
                        str+='1';
                        break;
                    case '২':
                        str+='2';
                        break;
                    case '৩':
                        str+='3';
                        break;
                    case '৪':
                        str+='4';
                        break;
                    case '৫':
                        str+='5';
                        break;
                    case '৬':
                        str+='6';
                        break;
                    case '৭':
                        str+='7';
                        break;
                    case '৮':
                        str+='8';
                        break;
                    case '৯':
                        str+='9';
                        break;
                    default:
                        str+=d[i];
                }
            }
            return str;
        }

        $(document).ready(function() {
             /*
             var defaults = {
             containerID: 'toTop', // fading element id
             containerHoverID: 'toTopHover', // fading element hover id
             scrollSpeed: 1200,
             easingType: 'linear'
             };
             */
            $item_template = `
                            <div class="col-md-4 port-grid">
                                <div class="col-sm-12 thumbnail text-center">
                                    <div class="product-container">
                                        <img src="images/products/#image-src#" alt="Product" class="image" style="width:100%;max-height:250px">
                                        <a>
                                            <div class="middle">
                                                <button type="button" class="btn btn-success btn-lg view-product" data-product-id="#open-btn#" data-toggle="modal" data-target="#myModal">প্রোডাক্ট দেখুন</button>
                                            </div>
                                        </a>
                                        <h4 id="product_name" style="display: inline-block; text-align=left">#product-name#</h4>
                                        <div class="ratingStar" id="#rateYo#" style="display: inline-block; text-align=right"></div>
                                    </div>
                                </div>
                            </div>`;

            function load_data(post_data, append=false) {
                $.post('products.php', post_data, function($data, $status) {
                    if($data=='no_data') $('#view_more').hide();
                    else {
                        $tmp = jQuery.parseJSON($data);
                        var count = all_products.length;
                        if(append===true) {
                            $tmp.forEach(function (x) {
                                all_products.push(x);
                            });
                        }
                        console.log(all_products);
                        $data = '';
                        $tmp.forEach( function(product) {
                            $data += $item_template.replace("#image-src#", product.image)
                                .replace("#open-btn#", count)
                                .replace("#rateYo#", count)
                                .replace("#product-name#", product.name);
                            count++;
                        });
                        if(append===true) {
                            $(".dynamic-load").append($data);
                            items_loaded+=limit;
                        }
                        else {
                            $(".dynamic-load").html($data);
                            items_loaded=limit;
                        }
                        $(".ratingStar").each(function () {
                            var id = $(this).attr("id");
                            //rating
                            $(this).rateYo({
                                rating: all_products[id].rating,
                                halfStar: true,
                                readOnly: true,
                                starWidth: "15px",
                                multiColor: {
                                    "startColor": "#FF0000", //RED
                                    "endColor"  : "#00FF00"  //GREEN
                                }
                            });
                        });
                    }

                });
            }

            $("#view_more").click(function () {
                load_data({offset:items_loaded, limit:limit, category:cat}, true);
            });

            load_data({offset:items_loaded, limit:limit, category:cat}, true);

            $.datetimepicker.setLocale('en_bn');
            $('#datetimepicker_mask').datetimepicker({
                minDate: <?php echo date("'Y/m/d'"); ?>
            }).on("change", function () {
                var en = $(this).val();console.log(en);
                $(this).val(en2bn(en));
            });

            $().UItoTop({ easingType: 'easeOutQuart' });

            $("#imgInp").change(function(){
                readURL(this);
            });


            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            //triggered when modal is about to be shown
            $(document).on("click", "#modal-bid-btn", function (e) {
                //get data-id attribute of the clicked element
                var id = $(this).data('product-id');
                $("#modal-prod-id").attr("value", all_products[id].product_id);
                $('#clock2').countdown(all_products[id].deadline, function(event) {
                    var tmp = en2bn(event.strftime('%D দিন %H:%M:%S'));
                    $(this).html(tmp);
                });
                refresh_bid();

                function refresh_bid() {
                    $.post('products.php', {refresh_bid: all_products[id].product_id}, function($data, $status) {

                        if($data!="error") {
                            $("#modal-highest-price").html($data);
                        }
                        else alert("রিফ্রেশ সম্ভব হচ্ছে না");
                    });
                    setTimeout(refresh_bid, 2000);
                }
            });
            $(document).on("click", ".view-product", function (e) {
                //get data-id attribute of the clicked element
                var id = $(this).data('product-id');
                //populate the textbox
                $('#clock').countdown(all_products[id].deadline, function(event) {
                    var tmp = en2bn(event.strftime('%D দিন %H:%M:%S'));
                    $(this).html(tmp);
                    if(tmp == "০০ দিন ০০:০০:০০") $("#modal-bid-btn").attr("disabled",'');
                    else $("#modal-bid-btn").removeAttr('disabled');
                });
                $(".modal-img").attr("src", "images/products/"+all_products[id].image);
                $("#modal-bid-btn").attr("data-product-id", id);
                $("#modal-product-name").html(all_products[id].name);
                $("#modal-seller").html(all_products[id].user_name);
                $("#modal-location").html("উৎপাদনস্থল: " + all_products[id].location);
                $("#modal-amount").html("পরিমাণ: " + all_products[id].amount + " " + all_products[id].unit);
                $("#modal-price").html("দাম: " + all_products[id].baseprice + " টাকা / " + all_products[id].unit);
                $("#modal-total-price").html("মোট দাম: " + all_products[id].total_baseprice + " টাকা");
                $("#modal-category").html("পণ্যের ধরণ: " + all_products[id].category);
                $("#modal-description").html(all_products[id].description);
                //rating
                $("#rateYo").rateYo({
                    rating: all_products[id].rating,
                    halfStar: true,
                    readOnly: true,
                    starWidth: "30px",
                    multiColor: {
                        "startColor": "#FF0000", //RED
                        "endColor"  : "#00FF00"  //GREEN
                    }
                });
            });
        });
    </script>
</body>
</html>