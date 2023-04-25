# Dexter Loor | Coding Exam — Customers API

Quick notes:

I. Installation
1) Pull the working code from the main branch of this repo.
2) Once pulled, find the SQL dump of the DB structure in bin/sql/customers_api.sql
3) Run composer update (make sure you have composer globally installed) to download the Symfony dependencies
4) (Make sure you have MAMP, XAMPP or WAMP turned on) cd to the project folder of this Symfony app and run the commmand symfony server:start

(Item #4 assumes that you have Symfony CLI installed. If none, follow this instruction to download Symfony CLI: https://symfony.com/download)

II. Run the import command
1) cd to the project folder of this Symfony app
2) Run the command bin/console import-customers to begin the import process

III. API endpoints
1) Visit http://127.0.0.1:8000/customers to get the list of customers
2) Visit http://127.0.0.1:8000/customers/5 to get the specific details of customer with ID number 5.
