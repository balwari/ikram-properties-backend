## Steps to setup the project Backend

Step 1 :

git clone url

Step 2 : 

cd ikram-properties

Step 3 :

cp .env-original .env

check below values in .env file

API_KEY=PasteApiKeyHere
DB_DATABASE=ikramproperties
DB_USERNAME=root
DB_PASSWORD=writePasswordHere

Step 4 :

composer install

Step 5 :

create database with name ikramproperties

Step 6 :

php artisan migrate

Step 7 :

php artisan key:generate

Step 8 :

php artisan serve --port=8009

Step 9 :

goto terminal 

crontab -e

Add below code for scheduler to run

* * * * * cd /var/www/ikram-properties-backend && php artisan schedule:run >> /dev/null 2>&1

Write the path of project correctly

Restart server

If the above crontab not working you can run directly in terminal to check 

php artisan update:properties

Step 10 :

If you are facing any errors any time run this :

php artisan optimize

Now check in database properties will be inserting/updating coming from api

Give proper permissions to public folder

USE this url for Vuejs app (ikram-properties-ui) :

http://localhost:8009/api/v1/
