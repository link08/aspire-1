# Aspire API's V1
Aspire APIs can be used to build a custom loan application. Using these APIs any authenticated user can apply for loans, generate EMI installments and make EMI payments.

Read API documentation - [Postman Documentation](https://documenter.getpostman.com/view/665369/TzeTJpkx)
# Quick Installation
### Requirements
Composer, PHP 7.3 or greator
Clone the repository.
```sh
git clone https://github.com/mukul20/aspire.git
```
Switch to the repo folder
```sh
cd aspire
```
Install all the dependencies using composer
```sh
composer install
```
### Environment variables
Copy `.env.example` file to `.env` file and make configuration changes (if required)
```sh
cp .env.example .env
```
### Database
By default, for the ease of setup and testing, this project uses sqlite `database.sqlite` which should placed in database directory.
Use following command to create sqlite db in database directory
```sh
touch database/database.sqlite
```
Once database is configured, we are ready to setup our application. Following artisan command will setup a fresh Aspire application with database migrations, seeds, client secret (for Laravel Passport) and starts the development server.
```sh
php artisan aspire:setup
```
Make sure storage directory has proper permissions/ownership for application to access log and cache files.
***
# Consuming API's
The API endpoint can now be accessed at
```sh
http://localhost:8000/api
```
Test Credentials
| Test User Email   | Password  |
| -----------       |  ----     |
| `test@test.com`   | `pass`    |
 
Request headers

| Key           | Value                 | Required  |
| -----------   | -----------           |  ----     |
| Content-Type  | application/json      | Yes       |
| Authorization | Bearer <accessToken>  | Yes       |

### Authentication
This applications uses Laravel Passport to handle authentication. The token is passed with each request using the Authorization header. The authentication middleware handles the validation and authentication of the token. Please check the following sources to learn more about Larave Passport
> [Laravel Passport](https://laravel.com/docs/8.x/passport)
Laravel Passport provides OAuth 2 authentication for API's, but for the ease of testing and limited scope of the assignment this application issues API tokens without the complication of OAuth.
Multiple access tokens can be issued for a user.
***
# Code overview
This project uses Repository Design Pattern.

* `.env` - Environment variables can be set in this file.

### Dependencies
* `Laravel Passport` - For authentication.
### Folders
This project uses Repository Design Pattern
* `app/Models` - Contains all the Eloquent models
* `app/Http/Controllers/API/v1` - Contains all the API controllers
* `app/Http/Middleware` - Contains the auth middleware
* `app/Http/Providers` - Contains repository service provider
* `app/Http/Repositories` - Contains repository interfaces
* `app/Http/Repositories\EloquentRepositories` - Contains eloquent repository implementing their interfaces
* `app/Http/Services\EmiService` - Contains EMI generation and transaction service
* `app/Http/Utility` - Contains Utility class for formating API responses.
* `config` - Contains all the application configuration files
* `database/factories` - Contains the model factory for all the models
* `database/migrations` - Contains all the database migrations
* `database/seeds` - Contains the database seeder
* `routes/api/v1` - Contains all the api routes defined in api.php file
* `tests` - Contains all the application tests
* `tests/Feature` - Contains all the API tests
### Running Test Cases

```sh
vendor/bin/phpunit ./tests
```