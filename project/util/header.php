<nav class="navbar navbar-expand-lg navbar-light shadow navigation-zlks1q fixed-top">
    <a class="navbar-brand" href="/">Learn.com</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Homepage</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="course.php">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create-new-course.php">Create New Course</a>
            </li>
        </ul>
        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { ?>
            <a href="login.php" class="btn btn-primary btn-lkl23 mr-1">Login</a>
            <a href="register.php" class="btn btn-secondary btn-lkl23">Register</a>
        <?php } else { ?>
            <a href="php/logout_action.php" class="btn btn-danger btn-lkl23 mr-1">Logout</a>
        <?php } ?>

    </div>
</nav>