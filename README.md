## About Exchange Office

Exchange Office is a sample application emulating a currency exchange website.
It supports the following functionalities:

- Adding surcharges and discounts to single currencies
- Updating currencies by using external API (CurrencyLayer supported)
- Creating orders and sending emails after their creation

## Installation steps

These instructions are meant for running this project in the context of a local developer environment and might not be suitable for a production environment.
The steps below require a working Docker platform.

1. After cloning the repository, create a configuration using the .env.example as a template. This can be achieved by using the following command `cp .env.example .env`
2. After you have a copy of the configuration template, proceed to setting up the parameters inside of it:
    1. Setup the database connection. If using this guides preferred way(through sail), fill the following parameters with the following values:
    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=exchange_office
    DB_USERNAME=sail
    DB_PASSWORD=password
    ```
    2. Setup the mailing system by filling the necessary `.env` parameters. For SMTP, fill out the `MAIL_*` parameters inside of the `.env` file. For more information visit: [Laravel Mail: Configuration](https://laravel.com/docs/10.x/mail#configuration)
    3. To specify the recipient for created orders, please fill the `NOTIFICATIONS_RECIPIENT` parameter
    4. To enable the CurrencyLayer API integration, place your access key as the value for the `CURRENCY_LAYER_ACCESS_KEY` parameter inside of your `.env` file
3. Next, install the neccessary composer dependencies and set the application key by running the following command inside of the project's root directory:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    sh -c "composer install --ignore-platform-reqs && \
    php artisan key:generate"
```
4. After installing the dependencies, start up the docker services by running `./vendor/bin/sail up`. If you wish to run the services in the background run `./vendor/bin/sail up -d` instead.
5. Run database migrations by running `./vendor/bin/sail artisan migrate`
6. Seed the database by running the following command from inside of the project root directory: `./vendor/bin/sail artisan db:seed`
7. Access the frontend at http://localhost

## CurrencyLayer Integration

To update existing currency exchange rates run the command `./vendor/bin/sail artisan currency:update`

To setup daily updates to the exchange rates follow the setup guide at [Laravel Task Scheduling: Running the Scheduler](https://laravel.com/docs/10.x/scheduling#running-the-scheduler)
