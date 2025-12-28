<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
    </head>
    <body>
        <div class="dashboard-container">
            <!-- Sidebar Navigation (Left side) -->
            <aside class="sidebar">
                <div class="logo"> Short URL</div>
                <nav>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Settings</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <header class="top-navbar">
                    <div class="welcome-message">Welcome,  {{session('userName')}}</div>
                    <!-- Logout Menu (Right side) -->
                    <div class="logout-menu">
                        <a href="/logout" class="logout-btn">Logout</a>
                    </div>
                </header>

                <!-- Main Dashboard Area -->
                <div class="content-area">
                    <h1>Main Content</h1>
                    <p>Your dashboard content goes here.</p>
                </div>
            </main>
        </div>
    </body>
</html>
