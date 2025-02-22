# Video Game Management API

## Overview

"A Laravel-based API for managing video game collections with user authentication,
role-based access control, and a review system."

## Key Features

-   User authentication with roles(Admin and regular user)
-   Game management (CRUD operations)
-   Game filtering and sorting
-   Game reviews system

## Technologies & Versions

-   **PHP**: 8.1
-   **Laravel**: 10.XX
-   **Composer**: 2.6
-   **Database**: SQLite
-   **ORM**: Laravel Eloquent
-   **Authentication**: Laravel Sanctum
-   **Docker**: It contains both the database and the API itself (optional)

## Prerequisites

[PHP](https://www.php.net/manual/en/install.php) 8.1+
[Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) 2.6+
[Docker](https://docs.docker.com/get-started/get-docker/) (If you intend to use it)

## Installation(Docker)

1. Clone the repository

```bash
git clone https://github.com/Styri/Videogame-Management-API
cd Videogame-Management-API
```
2. Copy environment file:

```bash
cp .env.example .env
```

3. Start Docker containers:

```bash
docker-compose up -d
```

4. Enter the container and set up Laravel

Enter:
```bash
docker-compose exec app bash
```
Install dependencies:
```bash
composer install
```
Generate app key:
```bash
php artisan key:generate
```
Create tables and seed them:
```bash
php artisan migrate --seed
```
Exit: Ctrl + c or:
```bash
exit
```

### The API will be accessible at http://localhost:8000/api

## Installation(Non Docker)

> [!NOTE]
> If you're installing PHP for the first time, in order to follow the installation steps, you may need to uncomment (remove the * from the front) the following extensions in your `php.ini` file (which you can find in the root of your PHP directory after installing Composer):
>```
> extension=fileinfo
> extension=pdo_sqlite
> extension=sqlite3
>```

1. Clone the repository:

```bash
git clone https://github.com/Styri/Videogame-Management-API
cd Videogame-Management-API
```

2. Create SQLite database(while in root directory):

```bash
touch database/database.sqlite
```

3. Copy environment file:

```bash
cp .env.example .env
```

4. Install dependencies:

```bash
composer install
```

5. Generate app key:

```bash
php artisan key:generate
```

6. Create tables and seed them:

```bash
php artisan migrate --seed
```

OR create the tables without seeding them:

```bash
php artisan migrate
```

7. Start the local server:

```bash
php artisan serve
```

## API Documentation

### Postman Collection

A Postman collection is provided in the `apidocs` folder for easy API testing and exploration.

#### Importing the Postman Collection

1. Open Postman
2. Click "Import" button (top-left corner)
3. Drag and drop the collection file
4. Click "Import"

### Accessing Endpoints

-   After importing, you'll see the full list of API endpoints
-   Most endpoints require authentication (except register and login)
-   Use the login endpoint to obtain an access token
-   Add the access token to the Authorization header for the protected routes

## API Endpoints

### Authentication

User registration
``` bash
POST /api/register
``` 
User login
``` bash
POST /api/login
```
User logout
``` bash
POST /api/logout
```


### Games

List all games
``` bash
GET /api/games 
``` 
Create a new game
``` bash
POST /api/games
``` 
Retrieve specific game
``` bash
GET /api/games/{game_id}
``` 
Update a game
``` bash
PUT /api/games/{game_id}
``` 
Delete a game
``` bash
DELETE /api/games/{game_id}
``` 

### Personal Games(for dashboard use)

List user's personal game collection
``` bash
GET /api/my-games
```

### Game Reviews

 List reviews for a specific game
``` bash
GET /api/games/{game_id}/reviews
``` 
Add a review to a game
``` bash
POST /api/games/{game_id}/reviews: 
```

## Authorization

Two roles are available: Regular User and Admin
- Regular users can fully manage their own games.
- Admin users can fully manage games of any user.

### User Roles

For testing purposes, initial seeding creates:
- 1 Admin user: `admin@gamehub.com`
- 50 randomly generated Regular Users

### Login Credentials of admin user(created by seeding for testing)

- Admin User:
  - Email: `admin@gamehub.com`
  - Password: `password123`


## Next step(s)

- Extend filtering capabilities