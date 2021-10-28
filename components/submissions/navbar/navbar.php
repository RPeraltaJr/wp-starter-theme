<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid px-5">
    <h1 class="navbar-brand mb-0">
      <a href="<?php echo home_url(); ?>/wp-admin" class="text-white">Submissions</a>
    </h1>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-user-circle mr-1"></span>
                <?php echo $user->user_login; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo home_url(); ?>/wp-admin/profile.php">Profile</a>
                <a class="dropdown-item" href="<?php echo wp_logout_url(); ?>">Logout</a>
            </div>
        </li>
    </ul>
  </div>
</nav>
