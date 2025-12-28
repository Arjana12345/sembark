<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <style>
            /* Base Layout for Full Page Height */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Inter', sans-serif; 
                background-color: #f8fafc; 
                display: flex; 
                height: 100vh; /* Ensures full viewport height */
                overflow: hidden; /* Prevents body scroll */
            }

            /* Fixed Sidebar */
            .sidebar {
                width: 260px;
                background-color: #1e293b;
                color: white;
                display: flex;
                flex-direction: column;
                flex-shrink: 0;
            }
            .sidebar-header { padding: 2rem; font-size: 1.5rem; font-weight: bold; border-bottom: 1px solid #334155; }
            .sidebar-nav { flex-grow: 1; padding: 1rem 0; }
            .sidebar-nav a { display: block; padding: 0.75rem 2rem; color: #94a3b8; text-decoration: none; transition: 0.3s; }
            .sidebar-nav a:hover, .sidebar-nav a.active { background: #334155; color: white; }

            /* Main Wrapper */
            .main-wrapper {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                overflow: hidden; /* Contains scroll within this area */
            }

            /* Top Header with Logout on Right */
            .header {
                height: 70px;
                background: white;
                border-bottom: 1px solid #e2e8f0;
                display: flex;
                align-items: center;
                justify-content: space-between; /* Pushes brand/search and logout to ends */
                padding: 0 2rem;
                flex-shrink: 0;
            }
            .user-profile { display: flex; align-items: center; gap: 1rem; }
            .logout-link {
                color: #ef4444;
                text-decoration: none;
                font-weight: 600;
                padding: 0.5rem 1rem;
                border: 1px solid #fee2e2;
                border-radius: 6px;
                transition: 0.2s;
            }
            .logout-link:hover { background: #fee2e2; }

            /* Scrollable Content Area */
            .content {
                padding: 2rem;
                overflow-y: auto; /* Makes content scrollable independently */
                flex-grow: 1;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }
            .card { background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        </style>
    </head>

    <body>
        <!-- Main Content Area -->
        <div class="main-wrapper">
            <header class="header">
                <div class="search-bar">
                    <strong><a href="/dashboard">Go to Dashboard</a>
                    @if (session('rolId') == 1)
                        <p>
                            Super Admin User
                        </p>
                    @elseif (session('rolId') == 2)
                        <p>
                            Admin User
                        </p>
                    @else
                        <p>
                            Member User
                        </p>
                    @endif
                    </strong>
                </div>
                <div class="user-profile">
                    <span>Welcome, {{session('userName')}}</span>
                    <a href="/logout" class="logout-link">Logout</a>
                </div>
            </header>
              
                <!-- Main Dashboard Area -->
            <main class="content">
                <div class="content-area">
                    @if (session('success'))
                        <div style="color:green">
                            {{ session('success') }}
                        </div>
                    @else
                        <div style="color:red">
                            {{ session('fail') }}
                        </div>
                    @endif
                    <p>Invite New Client</p>
                        <form method="post" action="{{ route('client.store') }}">
                            @csrf
                            @method('post')
                            <input type="text" name="client_name" placeholder="Client Name">
                            <input type="text" name="client_email" placeholder="Client Email">
                            <input type="submit" name="Submit">
                        </form>
                </div>
            </main>
        </div>
    </body>
</html>
