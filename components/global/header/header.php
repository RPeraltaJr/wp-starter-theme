<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta http-equiv="Cache-control" content="public">
    <meta name="description" content="<?php if(isset($page->meta) && !empty($page->meta['desc'])): echo $page->meta['desc']; else: echo ""; endif; ?>">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#FFFFFF"/>

    <title>Company | Careers<?php if(isset($page->meta) && !empty($page->meta['title'])): echo " - {$page->meta['title']}"; endif; ?></title>
    <!-- <link rel="shortcut icon" href="assets/build/img/favicon.ico" type="image/x-icon"> -->

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/build/css/<?php echo $page->type; ?>.min.css?ver=<?php echo $version; ?>">
    
    <!--[if lte IE 8]>
      <meta http-equiv="refresh" content="0" url="http://browsehappy.com/" />
      <script type="text/javascript">
      /* <![CDATA[ */
      window.top.location = 'http://browsehappy.com/';
      /* ]]> */
      </script>
    <![endif]-->

  </head>
  <body>