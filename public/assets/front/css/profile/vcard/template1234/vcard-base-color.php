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

.service-section-widget.service-section-three .service-item .content h4 {
    color: <?php echo $color ?>;
}
.main-btn.btn-color-three {
    background-color: <?php echo $color ?>;
}
.testimonial-section-widget.testimonial-section-three .testimonial-arrows .slick-arrow, .portfolio-section-widget.portfolio-section-three .portfolio-arrows .slick-arrow {
    background-color: <?php echo $color ?>;
}
.video-section-widget.video-section-three .embed-responsive {
    border: 10px solid <?php echo $color ?>;
}
.service-section-widget.service-section-four .service-item .content h4 {
    color: <?php echo $color ?>;
}
.testimonial-section-widget.testimonial-section-four .testimonial-arrows .slick-arrow, .portfolio-section-widget.portfolio-section-four .portfolio-arrows .slick-arrow {
    background-color: <?php echo $color ?>;
}
.video-section-widget.video-section-four .embed-responsive {
    border: 10px solid <?php echo $color ?>;
}
.contact-section-widget.contact-section-four .form_control {
    border-color: <?php echo $color ?>;
}