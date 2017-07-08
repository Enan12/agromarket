<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 4/16/2017
 * Time: 1:56 PM
 */
?>
<div class="header head-top" id="home">
    <div class="container container-top" >
        <div class="header-top ">
            <?php require_once "navbar_items.php"; ?>
        </div>
        <?php
        if($page=="products")
            echo "
            <div>
                <div id=\"mySidenav\" class=\"sidenav\">
                    <a href=\"javascript:void(0)\" class=\"closebtn\" onclick=\"closeNav()\">&times;</a>
                    <a href=\"products.php?cat=শস্য \">শস্য </a>
                    <a href=\"products.php?cat=শাঁক\">শাঁক </a>
                    <a href=\"products.php?cat=সবজি\">সবজি </a>
                    <a href=\"products.php?cat=ফল\">ফল </a>
                    <a href=\"products.php?cat=মস্লা \">মসলা </a>
                    <a href=\"products.php?cat=মাছ \">মাছ </a>
                    <a href=\"products.php?cat=অন্যান্য \">অন্যান্য </a>
                </div>
    
    
    
                <script>
                    function openNav() {
                        document.getElementById(\"mySidenav\").style.width = \"auto\";
                        document.getElementById(\"mySidenav\").style.paddingLeft = \"3%\";
                        document.getElementById(\"mySidenav\").style.paddingRight = \"3%\";
                    }
    
                    function closeNav() {
                        document.getElementById(\"mySidenav\").style.width = \"0\";
                        document.getElementById(\"mySidenav\").style.paddingLeft = \"0\";
                        document.getElementById(\"mySidenav\").style.paddingRight = \"0\";
                    }
    
                    if(window.screen.availWidth > 800) openNav();
                </script>
            </div>";
        ?>
        <div class="logo logo1">
            <a href="index.php">এগ্রোমার্কেট</a>
        </div>

        <br>
    </div>
    <!--edited codes from here to ad search and category below logo.....-->
    <?php
    if($page=="products") echo "
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-8 col-md-offset-2\">
                <div class=\"input-group\">
                    <input type=\"hidden\" name=\"search_param\" value=\"all\" id=\"search_param\">
                    <input type=\"text\" class=\"form-control\" name=\"x\" placeholder=\"সার্চ করুন...\">
                        <span class=\"input-group-btn\">
                            <button class=\"btn btn-default\" type=\"button\"><span class=\"glyphicon glyphicon-search\"></span></button>
                        </span>
                </div>
            </div>
        </div>
    </div>";
    ?>
    <!--ended editing-->
</div>
