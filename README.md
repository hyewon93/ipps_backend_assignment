## Setup

1. Composer Install
2. php artisan --version (version check)
3. Change database.php and .env
4. php artisan key:generate
5. php artisan serve
6. Access 'http://127.0.0.1:8000/'


## Authentication
User information is hardcoded in the [web.php]. Insert the info in DB and access 'https://127.0.0.1:8000/auth'.


## Endpoint
- [Achievements and badges for a user](http://127.0.0.1:8000/users/{userID}/achievements)