## About Project

This project demonstrates how to build a basic web application with Laravel to provide basic functions:

- Authentication

- CSV file upload and insert to db.

- Create worker to curl google search page using uploaded keywords.

## Pre-requirements
- PHP >= 7.1.3

- PostgreSQL 12.2

- Git

- Composer

## How to run it

1. First, clone the repo:

   ```
   $ git clone git@github.com:nhutle/laravel-crawler-app.git
   ```

2. Create `.env` file by rename `.env.local` to be `.env`

3. Create a database locally named ``laravel-crawler-app``

4. Run following commands to initial project

   ```
    $ composer install
   ```

   ``` 
   $ php artisan key:generate
   ```

   ```
   $ php artisan migrate
   ```

   ```
   $ php artisan db:seed
   ```

   ```
   $ php artisan serve
   ``` 
   (or you can use XAMPP/XAMPP as you want to run server)

5. Run cron job

   ```
   $ php artisan queue:listen --timeout=0 --delay=0 --tries=5
   ```

6. Retry a failed job

   ```
   $ php artisan queue:retry $jobId
   ```

7. Run unit test

   ```
   $ ./vendor/bin/phpunit tests/
   ```

8. Default username and password generated by migration, feel free to change it on UserSeeder.php 

   ```
   username: nhutle
   ```

   ```
   password: nhutle
   ```
   
9. Find sample csv under name `keywords.csv`

## Routes

#### Web
| Method | Path | Description
| ------ |:-----| --------- |
| GET | /login | Login view
| POST | /login | Login with username/password
| GET | /upload | Upload view
| POST | /upload | Upload CSV file
| POST | /process_file | Process file
| GET | /statistics | Statistics view

#### API
| Method | Path | Description
| ------ |:-----|:--------- |
| POST | /api/login | Login with username/password to generate JWT
| POST | /api/upload | Upload CSV file
| POST | /api/process_file | Process file
| GET | /api/statistics | Get statistics

## Live demo

I have tried to deploy the project on AWS Elastic Beanstalk but can not access it via SSH from my Windows, hence you can not log-in for sure. I will need to fix it then, at least succeed to ssh to my EC2.

Link to [aws demo](http://laravelcrawlerapp-env.eba-surpq9em.ap-southeast-1.elasticbeanstalk.com)

## Issues

- ~~No API implementation at the moment, but it's on my bucket list~~

- When click on `Open it` <- Google Page Cache, somehow its JavaScript redirects user to a new page.

- Live demo is broken.

- Basic implementation. It took me 2 days to realize that PHP CodeIgniter fails to manage crob job efficiently, then Laravel came out. I spent 2 more days to learn Laravel and this app has been build in 3 days only.

## Security Vulnerabilities

If you discover a security vulnerability within this app, feel free to create issues and pull requests. I will take a look and fix them asap.

## License

MIT License
