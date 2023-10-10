<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <a class="navbar-brand" href="home.php">Online Diagnostic Center</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="update_profile.php">Welcome <?php echo $_SESSION['email']; ?></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="update_profile.php">Update Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="test_status.php">Test Status</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>