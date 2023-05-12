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
.user-info-wrapper .user-box:after{
	background-color: <?php echo $color; ?>5a !important;
}