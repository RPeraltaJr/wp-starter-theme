  <?php if ( is_user_logged_in() ): wp_footer(); endif; ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <?php 
    if( isset($page_setting->plugins) && !empty($page_setting->plugins) ): 
      foreach($page_setting->plugins as $plugin):
        echo "<script src='{$plugin}'></script>\n";
      endforeach;
    endif; 
  ?>
  <script src="<?php echo get_template_directory_uri(); ?>/assets/build/js/<?php echo $page_setting->type; ?>.min.js"></script>
<?php wp_footer(); ?>
</body>
</html>
