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
    --color-primary: <?php echo $color; ?>;
    --color-offwhite: <?php echo $color; ?>08;
}
.portfolio-item .portfolio-thumb .portfolio-link {
  background-color: <?php echo $color; ?>B3;
}