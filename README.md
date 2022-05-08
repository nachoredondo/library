# DOCUMENTATION Catalog 


## Installation & upgrades

1. Install XAMPP (or Apache+MySQL+PHP individually). It is recommended for development environments to have phpMyAdmin or any other database management tool (XAMPP includes it). Uncomment and fill in the lines in config.php to connect with db (between lines 11 and 16), it's currently set up for heroku production.

1. Load the database with files from the sql folder:
	- db_structure.sql: it has all the structure tables to run the application..


## Initial File structure

```
/ (root)
├── api/ - php scripts to connect classes and front
├── assets/
├── classes/ - application logic (PHP classes)
├── doc/ documentation for general and technical user
├── sql/ - structure database
├── views/ - script front php
├── .gitignore
└── README.md
```





