# Library documentation 


## Installation and configuration

1. Install XAMPP (or Apache+MySQL+PHP individually). It is recommended for development environments to have phpMyAdmin or any other database management tool (XAMPP includes it).

1. Load the database with files from the sql folder:
	- db_structure.sql: it has all the structure tables to run the application.

1. Set credentials database connection in config.php.


## Structure of the files and folders in the root directory

```
/ (root)
├── api/ -> php scripts to connect classes and front
├── assets/
├── classes/ -> application logic (PHP class)
├── doc/ -> documentation for technical user
├── sql/ -> database structure
├── views/ -> script front php
├── .gitignore
└── README.md
```
