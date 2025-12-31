#Sembark Test Repo
====================
About short url

Visited Website
==============
https://medium.com/@pichitwandee/laravel-10authentication-registration-login-logout-with-middleware-53aea41c3aa9

Database 
==========
name:sembark
Tables:
        users
        client
        short_url
        url_hits

Controllers:
============
        Auth
        User
        Client
        ShortUrl

Models:
======
    Client
    roles
    ShortUtl
    UrlHits
    User

Roles:
=====
SuperAdmin  - Display all client list and all short urls
            - Action  invite client (create client)
 
Admin  - Display short URLs and Team Memebr withing company(client)
       - Action create short url and create/invite user in its own company
 
Member  -   Display their own short urls
        -   Action  create short urls

Note: 1) here invite s basically acreate action on DB
      2) To create team member i am using default password = 12345678
      3) I manually added entry in url_hits table

Steps:
======
1) git clone git@github.com:Arjana12345/sembark.git
2) Run - composer install
3) set permission to directory sudo chmod -R 777
4) Run - php artisan serve 
5) Run copy .env.example .env
6) update db creadentials in .env file
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sembark
    DB_USERNAME=DB_user_name
    DB_PASSWORD=DB_password

7) php artisan key:generate
8) php artisan migrate
    OR
    upload db.sql which is shared