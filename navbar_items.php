<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 4/16/2017
 * Time: 2:50 PM
 */
?>
<div class="top-menu">
    <span class="menu"><img src="images/nav.png" alt=""/> </span>
    <ul style="font-size: larger">
        <li><a href="index.php" <?php if($page=="index") echo 'class="active"' ?>>হোম</a></li><label>|</label>
        <li><a href="products.php" <?php if($page=="products") echo 'class="active"' ?>>মার্কেট</a></li><label>|</label>
        <li><a href="bidings.php" <?php if($page=="blog") echo 'class="active"' ?>>নিলাম</a></li><label>|</label>
        <li><a href="contact.php" <?php if($page=="contact") echo 'class="active"' ?>>যোগাযোগ</a></li><label>|</label>
        <?php
        if($login_done==1)
            echo "<li><a href=\"login.php?usage=logout\">লগআউট</a></li>";
        else
            echo "<li><a href=\"login.php\"" . (($page=="login") ? 'class="active"' : '') . " >লগইন/রেজিস্টার</a></li>";
        ?>
    </ul>
    <!-- script for menu -->

    <script>
        $("span.menu").click(function(){
            $(".top-menu ul").slideToggle("slow" , function(){

            });
        });
    </script>
    <div class="clearfix"></div>
</div>
