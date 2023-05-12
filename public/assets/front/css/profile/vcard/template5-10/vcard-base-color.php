<?php
header("Content-type: text/css; charset: UTF-8");

if(isset($_GET['color']))
{
  $color = '#'.$_GET['color'];
}
else {
  $color = "'" . $color . "'";
}
?>
:root {
    --red-dark: <?php echo $color ?>;
}
.admin-box-one:after {
    background-color: <?php echo $color ?>E6;
}
.contact-info-two .info-box .icon i {
    color: <?php echo $color ?>;
}
.admin-box-two .admin-info-box .user-info .share-btn, .admin-box-two .admin-info-box .user-info .user-social ul li a {
    color: <?php echo $color ?>;
}
.admin-box-two .admin-info-box .user-info .user-btn {
    color: <?php echo $color ?>;
}
.admin-box-two:after {
    background-color: <?php echo $color ?>D9;
}
.admin-box-two .admin-info-box .user-thumb img {
    border: 5px solid <?php echo $color ?>;
}
.contact-info-two .info-box {
    background-color: <?php echo $color ?>1A;
}
.page-area-two .section-widget h3.widget-title:after {
    background-color: <?php echo $color ?>;
}
.service-area-two .service-item .img-holder .img-overlay {
    background-color: <?php echo $color ?>E6;
}
.service-area-two {
    background-color: <?php echo $color ?>1A;
}
.project-area-two .project-item .text {
    background: <?php echo $color ?>1A;
}
.intro-area-two {
    background-color: <?php echo $color ?>1A;
}
.contact-area-two {
    background-color: <?php echo $color ?>1A;
}
.admin-box-three:after {
    background-color: <?php echo $color ?>E6;
}
:root{
    --pink-dark: <?php echo $color ?>;
}
.service-area-three .service-item .text {
    background-color: <?php echo $color ?>;
}
.project-area-three .project-item .text {
    background-color: <?php echo $color ?>;
}
.contact-area-three .contact-form .form-btn {
    background-color: <?php echo $color ?>;
}
.service-area-four {
    background-color: <?php echo $color ?>14;
}
.about-area-four {
    background-color: <?php echo $color ?>14;
}
.project-area-four {
    background: <?php echo $color ?>14;
}
.testimonial-area-four {
    background: <?php echo $color ?>14;
}
.contact-area-four {
    background-color: <?php echo $color ?>14;
}
.admin-box-four:after {
    background-color: <?php echo $color ?>F0;
}
.service-area-four .service-item .text.bg-one {
    background-color: <?php echo $color ?>;
}
.project-area-four .project-item .img-holder .img-overlay.bg-one {
    background-color: <?php echo $color ?>CF;
}
.admin-box-five:after {
    background-color: <?php echo $color ?>A1;
}
:root {
    --green: <?php echo $color ?>;
}
.testimonial-area-three .testimonial-item .author-thumb img {
    border: 5px solid <?php echo $color ?>;
}
:root{
    --blue-dark: <?php echo $color ?>;
}
.project-area-six .project-item .img-holder .img-overlay {
    background-color: <?php echo $color ?>EB;
}
.testimonial-area-six .testimonial-item {
    background-color: <?php echo $color ?>EB;
}
.admin-box-six .admin-info-box .user-info .user-btn {
    background-color: <?php echo $color ?>38;
}
.contact-info-six .info-box .icon {
    background-color: <?php echo $color ?>38;
}