# Integer Spiral API

[Sample Project](https://integer-spiral-api.guvensen.com/)


# Database

Create Database;  
````
CREATE DATABASE integer_spiral;
````

Create Table;
````
CREATE TABLE public.integer_layout
(
    id SERIAL CONSTRAINT id PRIMARY KEY,
    created_at TIMESTAMP DEFAULT NOW(),
    value TEXT
);
````

## Install with composer:

```composer require zircote/swagger-php```  
```composer require vlucas/phpdotenv```