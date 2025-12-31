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
                   <p>All Client</p>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Users</th>
                                    <th>URLs</th>
                                    <th>Total URLs Hits</th>
                                </tr> 
                            </thead>
                            <tbody>
                                @if (count($client_list))
                                    @foreach($client_list as $this_client)
                                    <tr>
                                        <td>{{ $this_client->client_name }} <p style="color: #6D6968">{{ $this_client->client_email }}</p></td>
                                        <td>{{ $this_client->total_users }} </td>
                                        @if (array_key_exists($this_client->id ,$client_total_urls))
                                            <td>{{ $client_total_urls[$this_client->id] }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                        @if (array_key_exists($this_client->id ,$client_total_hits))
                                            <td>{{ $client_total_hits[$this_client->id] }}</td>
                                        @else
                                            <td>0</td>
                                        @endif
                                    </tr> 
                                    @endforeach
                                @else
                                <tr><td>Data not found.</td></tr>
                                
                                @endif
                             
                            </tbody>
                            
                        </table>
                           @if (count($client_list))
                                {{ $client_list->links() }} 
                                @endif
                    </div>
                </div>
            </main>
               
        </div>
    </body>
</html>
