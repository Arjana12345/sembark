<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
    </head>
    <body>
        <div class="dashboard-container">
            <div class="logo"> Short URL - 
                @if (session('rolId') == 1)
                    <p>
                        Super Admin Dashboard
                    </p>
                @elseif (session('rolId') == 2)
                    <p>
                        Admin Dashboard
                    </p>
                @else
                    <p>
                        Member Dashboard
                    </p>
                @endif

            </div>
          

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
                    @if (session('rolId') == 1)
                        <h1>Clients <a href="/client/invite"><button type="button" class="primary">Invite</button></a></h1>
                        <table border="1">
                            <tr>
                                <th>Client Name</th>
                                <th>Users</th>
                                <th>URLs</th>
                                <th>Total URLs Hits</th>
                            </tr> 
                            @if ($client_list->count())
                                @foreach($client_list as $this_client)
                                <tr>
                                    <td>{{ $this_client->client_name }} <p>{{ $this_client->client_email }}</p></td>
                                    <td>Users</td>
                                    <td>URLs</td>
                                    <td>Total URLs Hits</td>
                                </tr> 
                                @endforeach
                            @else
                            <tr><td>Data not found.</td></tr>
                        @endif
                        </table>
                    
                    @endif
                </div>
            </main>
        </div>
    </body>
</html>
