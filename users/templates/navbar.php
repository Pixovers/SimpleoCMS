<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php 

    $currenturl = $_SERVER['PHP_SELF']; //create a variable containing the current URL?>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php 
      
          $activeIndex = (stripos($_SERVER['PHP_SELF'],'index') && empty($_GET['action']));
          $class = $activeIndex ?  'active' : '' ;
          echo $class;
          // check if we are in the index, and if GET is empty. then we print 'active'?> " href="index.php">Users</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php $activeIndex = (!empty($_GET['action'])&& $_GET['action'] ==='insert');  
          $class = $activeIndex ?  'active' : ''; echo $class; ?>" href="index.php?action=insert">
          <i class="fas fa-user-plus"></i>New User </a>
        </li>
     
  
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

