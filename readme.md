# Google Analytics Report of Metrics

This report allows to you are able to see a report with date, city and also device

### Installing

First of all, you should go to project root directory.

Run composer
```
composer install
```

Run NPM

```
npm install
```

Update .env file according to your credentials.

Run migrations
```
php artisan migrate
```

Before collecting data, should update Google Analytics credentials on .env file and put Analytics API JSON file under storage/app/analytics or any directory where you want. After putting the file, please update config/analytics.php.

All done!

## Built With

* [Laravel](https://laravel.com/) - The web framework used
* [MySQL](https://www.mysql.com/) - The database used
* [PHP 7.2.15](https://www.php.net/) - The language used

## Authors

* **Bilal Baraz** - [GitHub Account](https://github.com/bilalbaraz)
