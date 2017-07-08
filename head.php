<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 4/16/2017
 * Time: 1:14 PM
 */
require_once "dbconnect.php";
if(session_status()==PHP_SESSION_NONE){
    session_start();
}

$page = basename($_SERVER["SCRIPT_FILENAME"], '.php');
$login_done=isset($_SESSION["name"]);
$clientaddr = $_SERVER['REMOTE_ADDR'];
$login_timeout_sec = 86400;

$sql = "INSERT INTO `tbl_visitor`( `ipv4`) VALUES (?) ON DUPLICATE KEY UPDATE visit_count = IF(TIMESTAMPDIFF(MINUTE, last_access, NOW())>=5, visit_count+1, visit_count), last_access=NOW();";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $clientaddr);
    $stmt->execute();
}

if(isset($_SESSION["created"]))
{
    if((time() - $_SESSION['created']) > $login_timeout_sec-1) // Time in Seconds
    {
        session_destroy();
        header("Location:" . $_SERVER['HTTP_REFERER']);
    }
    else
    {
        $_SESSION['created'] = time();
        //echo "<h1 align='center'>Welcome ".$_SESSION["name"]. "</h1>";
        //echo "<h5 align='center'>Automatic Logout after 1 minute of inactive</h5>";
        //echo "<p align='center'><a href='indx.php'>Logout</a></p>";
    }
}

?>
<title>এগ্রোমার্কেট.কম</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<?php if(isset($_SESSION["name"])) echo "<meta http-equiv=\"refresh\" content=\"$login_timeout_sec\" >"; ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Cultivation Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
    Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic' rel='stylesheet' type='text/css'>
<link href="css/products.css" rel="stylesheet" type="text/css">
<script src="js/jquery-1.11.1.min.js"></script>

<script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!---- start-smoth-scrolling---->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".scroll").click(function(event){
            event.preventDefault();
            $('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
        });
    });
</script>
<!---End-smoth-scrolling---->
<link rel="stylesheet" href="css/swipebox.css">
<script src="js/jquery.swipebox.min.js"></script>
<script type="text/javascript">
    jQuery(function($) {
        $(".swipebox").swipebox();
    });
</script>
<!--Animation-->
<script src="js/wow.min.js"></script>
<link href="css/animate.css" rel='stylesheet' type='text/css' />
<script>
    new WOW().init();
</script>
<!---/End-Animation---->
