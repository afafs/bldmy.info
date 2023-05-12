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
    --primary-color: <?php echo $color; ?>;
    --yellow-light: <?php echo $color; ?>0D;
}
.navigation-wrapper .main-menu ul li a:after {
    background: linear-gradient(
252.28deg, <?php echo $color; ?> 23.53%, <?php echo $color2; ?> 97.18%);
}
.navigation-wrapper .nav-button a {
    background: linear-gradient(
201.22deg, <?php echo $color2; ?> 23.71%, <?php echo $color2; ?> 23.72%, <?php echo $color; ?> 77.29%);
}
.service-item .service-content:after {
    background: linear-gradient(
329.2deg, <?php echo $color; ?> 17.79%, <?php echo $color2; ?>99);
}
.testimonial-area .testimonial-item .author-info h5:before {
    background: linear-gradient(
269.9deg, <?php echo $color; ?> 9.79%, <?php echo $color2; ?> 91.6%);
}
.blog-area .blog-post-item .post-thumbnail .post-date .icon {
    background: linear-gradient(
219.57deg, <?php echo $color; ?> 14.92%, <?php echo $color2; ?> 82.82%);
}