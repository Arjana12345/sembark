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
        <div class="main-wrapper">
            <header class="header">
                <div class="search-bar">
                    <strong>Dashboard
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
          

            <!-- Main Content Area -->
            <main class="content">
                <!-- Main Dashboard Area -->
                <div class="content-area">
                    @if (session('rolId') == 1)
                        <h1>Clients <a href="/client/invite"><button type="button" class="button">Invite</button></a></h1>
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
                                @if ($client_list->count())
                                    @foreach($client_list as $this_client)
                                    <tr>
                                        <td>{{ $this_client->client_name }} <p style="color: #6D6968">{{ $this_client->client_email }}</p></td>
                                        <td>Users</td>
                                        <td>URLs</td>
                                        <td>Total URLs Hits</td>
                                    </tr> 
                                    @endforeach
                                @else
                                <tr><td>Data not found.</td></tr>
                                @endif
                                
                            </tbody>
                            
                        </table>
                        
                    @endif
                    {{ $client_list->links() }} 
                        <p><a href="{{route('client.index')}}">View All</a></p>
                </div>
                <br/><br/><br/>
                <div class="content-area">
                    @if (session('rolId') == 1)
                        <h1>Short URLs 
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
                                        <td>{{ $this_url->total_hits }}</td>
                                        <td>{{ $this_url->name }}</td>
                                        <td>{{ $this_url->created_at }}</td>
                                    </tr> 
                                    @endforeach
                                @else
                                <tr><td>Data not found.</td></tr>
                                @endif
                                
                            </tbody>
                            
                        </table>
                        
                    @endif
                    {{ $short_url_list->links() }} 
                    <p><a href="{{route('surl.index')}}">View All</a></p>
                </div>

            </main>
        </div>
        
        
    </body>
</html>
