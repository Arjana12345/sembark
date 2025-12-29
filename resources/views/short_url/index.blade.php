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
            .table-container {
                background: white;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                overflow: hidden; /* For rounded corners */
            }

            table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            thead {
                background-color: #f8fafc;
                border-bottom: 2px solid #e2e8f0;
            }

            th, td {
                padding: 1rem 1.5rem;
                font-size: 0.9rem;
            }

            tbody tr {
                border-bottom: 1px solid #f1f5f9;
                transition: background 0.2s;
            }

            tbody tr:hover {
                background-color: #f8fafc;
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
                   <p>All Generated URLs</p>
                    <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Short URL</th>
                                <th>Long URL</th>
                                <th>Hits</th>
                                <th>Client Name</th>
                                <th>Created On</th>
                            </tr> 
                        </thead>
                        <tbody>
                            @if ($short_url_list->count())
                                @foreach($short_url_list as $this_url)
                                <tr>
                                    <td>{{ $this_url->short_url }}</td>
                                    <td>{{ $this_url->long_url }}</td>
                                    <td>hits</td>
                                    <td>client name</td>
                                    <td>{{ $this_url->created_on }}</td>
                                </tr> 
                                @endforeach
                            @else
                            <tr><td>Data not found.</td></tr>
                            @endif
                            
                        </tbody>
                        
                    </table>
                    {{ $short_url_list->links() }} 
                      
                </div>
            </main>
               
        </div>
    </body>
</html>
