<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-success navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <?php if(isset($_SESSION['login_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
      </li>
    <?php endif; ?>
      <li>
        <a class="nav-link text-white"  href="./" role="button"> <large><b>Record System</b></large></a>
      </li>
    </ul>

   
  </nav>
  <!-- /.navbar -->
