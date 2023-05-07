<header>
    <nav class="navbar">
        <div class="container-fluid">
            <a href="index.php">Site.com</a>
            <?php if (!in_array(basename($_SERVER['PHP_SELF']), array('register.php', 'login.php'))): ?>
                <button onclick="window.location.href='logout.php';">
                    Logout
                </button>    
            <?php endif; ?>
        </div>
    </nav>
</header>