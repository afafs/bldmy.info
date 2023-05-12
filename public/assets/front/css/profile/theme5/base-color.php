<?php
header("Content-type: text/css; charset: UTF-8");

if(isset($_GET['color']))
{
  $color = '#'.$_GET['color'];
}
else {
  $color = "'" . $color . "'";
}

if(isset($_GET['color2']))
{
  $color2 = '#'.$_GET['color2'];
}
else {
  $color2 = "'" . $color2 . "'";
}
?>

:root {
    --primary-color: <?php echo $color2; ?>;
}
.primary-menu {
    background-color: <?php echo $color; ?>;
}
.user-box {
    background-color: <?php echo $color2; ?>;
}
.primary-menu .main-menu ul li:hover > a {
    background-color: <?php echo $color2; ?>;
}
.nav-social {
    background-color: <?php echo $color; ?>;
}
.hero-wrapper-one:after {
    background-color: <?php echo $color2; ?>CC;
}
.counter-wrapper-one:after {
    background-color: <?php echo $color; ?>D9;
}
.portfolio-item-one .portfolio-img .portfolio-content {
    background-color: <?php echo $color; ?>;
}
.testimonial-item-one:after {
    color: <?php echo $color; ?>;
}
.footer-wrapper-one {
    background-color: <?php echo $color2; ?>;
}
.back-to-top {
    background: <?php echo $color; ?>;
}
.service-item-one .service-content {
    border-left: 15px solid <?php echo $color; ?>;
}

.profile-card .profile-link ul li a.active,
.profile-card .profile-link ul li a:hover {
    background: var(--primary-color) !important;
}
