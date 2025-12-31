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

