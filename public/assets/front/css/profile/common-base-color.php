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

.single-page-details .single-page-wrapper .post-item .post-gallery-slider .slick-arrow {
    background-color: <?php echo $color; ?>;
}
.sidebar-widget-area .widget.categories-widget ul.widget-link li a:hover {
    color: <?php echo $color; ?>;
}
.sidebar-widget-area .widget.search-widget .form_group .search_btn {
    color: <?php echo $color; ?>;
}
.sidebar-widget-area .widget.categories-widget ul.widget-link li.active a {
    color: <?php echo $color; ?>;
}
.page-item.active .page-link {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
.page-link {
    color: <?php echo $color; ?>;
}
.breadcrumbs-section {
    background: <?php echo $color; ?>08;
}
.profile-card .profile-link ul li a.active,
.profile-card .profile-link ul li a:hover {
    background-color: <?php echo $color; ?> !important;
}
