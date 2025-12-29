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
            .button {
                background-color: #04AA6D;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                }
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
                    <p>Add New URL</p>
                        <form method="post" action="{{ route('surl.store') }}">
                            @csrf
                            @method('post')
                            <input type="text" name="short_url" placeholder="Short URL">
                            <input type="text" name="long_url" placeholder="Long URL">
                            <input type="submit" name="Submit" class="button">
                        </form>
                </div>
            </main>
        </div>
    </body>
</html>
