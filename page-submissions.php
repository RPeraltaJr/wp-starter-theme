<?php 

if (!is_user_logged_in()):
    exit("Please log in");
else:
    // * get user data
    $user = wp_get_current_user();
    // print_r($user->user_email);
endif;

$page_setting = (object) array(
    "type"      => "page-submissions",
    "name"      => "submissions",
    "meta"      => [
        "title" => "",
        "desc"  => "",
    ],
    "plugins"   => [
        "https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js",
        "https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js",
        "https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",
        "https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js",
        "https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.js",
        "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js",
    ],
);

// header 
include __DIR__ . "/components/global/header/header.php"; 

// navbar 
include __DIR__ . "/components/submissions/navbar/navbar.php"; 

// table 
include __DIR__ . "/components/submissions/table/table.php"; 

// footer
/*
<script>
  let templateUrl = '<?= get_bloginfo("template_url"); ?>';
  let baseUrl = '<?= home_url(); ?>';
</script>
*/
include __DIR__ . "/components/global/footer/footer.php";
