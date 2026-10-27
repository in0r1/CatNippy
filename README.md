CatNippy is a Vulnerable Catnip shop bulit on php 8.3 and laravel 10 used to present various Web Application Vulnerabilites

The practical part of my engineering diploma.


to make it run
```bash
composer install
npm install
npm run build
#create your onw .env
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

for supplychain demo refer to:
https://github.com/in0r1/breeze-vulnerable
(vuln branch idk how to get rid of other branches)
where you need to install it through composer and then replace web.php and few other files from this project to make it all work thogether

![cute cat](/CatNippy/public/images/catnippy.svg)
