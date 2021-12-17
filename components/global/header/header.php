<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=1, initial-scale=1.0, maximum-scale=2, minimum-scale=1.0">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta http-equiv="Cache-control" content="public">
    <meta name="description" content="<?php if(isset($page_setting->meta) && !empty($page_setting->meta->desc)): echo $page_setting->meta->desc; else: echo ""; endif; ?>">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#FFFFFF"/>
    
    <meta property="og:type" content="website"/> 
    <meta property="og:title" content="<?php if(isset($page_setting->meta) && !empty($page_setting->meta->title)): echo $page_setting->meta->title; else: echo ""; endif; ?>"/>
    <meta property="og:url" content="<?php if($page_setting->name != 'index'): echo basename($_SERVER['REQUEST_URI']); endif; ?>" />
    <meta property="og:image" content="<?php echo $img_path; ?>/og_img.jpg" /> 
    <meta property="og:description" content="<?php if(isset($page_setting->meta) && !empty($page_setting->meta->desc)): echo $page_setting->meta->desc; else: echo ""; endif; ?>" />
    <meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ) . " - " ?? ''; ?><?php the_title(); ?>" />

    <title><?php echo get_bloginfo( 'name' ) . " - " ?? ''; ?><?php the_title(); ?></title>
    <!-- <link rel="shortcut icon" href="<?php echo $img_path; ?>/favicon.ico" type="image/x-icon"> -->
    
    <?php wp_head(); ?>
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/build/css/<?php echo $page_setting->type; ?>.min.css?ver=<?php echo $version; ?>">

  </head>
  <body <?php body_class(); ?>>
