<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="public">

    <title><?php echo get_bloginfo( 'name' ) . " - " ?? ''; ?><?php the_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $img_path; ?>/tb_favicon.png" type="image/png">
    
    <?php wp_head(); ?>
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/build/css/<?php echo $page_setting->type; ?>.min.css?ver=<?php echo $version; ?>">

  </head>
  <body <?php body_class(); ?>>

    <a href='<?php if(!empty($page_setting->main)): echo $page_setting->main; endif; ?>' class='sr-only'>Skip to main content</a>
