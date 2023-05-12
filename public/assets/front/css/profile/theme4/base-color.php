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
    --primary-color: <?php echo htmlspecialchars($color) ?>;
}
.btn-link:after {
    background-color: <?php echo htmlspecialchars($color) ?>;
}
.date-bg-one {
    background-color: <?php echo htmlspecialchars($color) ?>14;
}
.portfolio-area .portfolio-item .portfolio-img .portfolio-content {
    background-color: <?php echo htmlspecialchars($color) ?>E6;
}
.testimonial-area .testimonial-item.item-one:after {
    color: <?php echo htmlspecialchars($color) ?>1A;
}
.breadcrumbs-area {
    background: linear-gradient(
103.3deg, <?php echo htmlspecialchars($color) ?>66 2.43%, <?php echo htmlspecialchars($color) ?>1A 46.64%, <?php echo htmlspecialchars($color) ?>1A 100%);
}