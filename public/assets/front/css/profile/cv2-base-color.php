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
	--color-primary: <?php echo $color; ?> !important;
}

.cv-wrapper .cv-header {
    background-color: <?php echo $color; ?>1a !important;
    border-bottom: 15px solid <?php echo $color; ?>3a !important;
}
.cv-wrapper .cv-header .user-thumb {
    border: 6px solid <?php echo $color; ?>4a !important;
}
.cv-wrapper .cv-header .user-thumb img {
    border: 2px solid <?php echo $color; ?> !important;
}