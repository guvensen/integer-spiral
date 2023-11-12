# Integer Spiral API

## About 
This API creates a table of x and y axes given. Starting from coordinate 0,0, it rotates clockwise and 
fills from outside to inside.

## Sample Project
[Link](https://integer-spiral-api.guvensen.com/)

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
    value JSON,
    x INTEGER NOT NULL ,
    y INTEGER NOT NULL ,
    created_at TIMESTAMP DEFAULT NOW()
);
````



## Install with composer:

```composer require zircote/swagger-php```  
```composer require vlucas/phpdotenv```

## Endpoints
| Method | Endpoint                                 | Description                    |
|--------|------------------------------------------|--------------------------------|
| POST   | /layout?x={int}&y={int}                  | Add a new layout               |
| GET    | /layout                                  | List layouts                   |
| GET    | /layout/{layoutId}                       | Find layout by ID              |
| GET    | /layout/{layoutId}/value?x={int}&y={int} | Get value of layout            |
| GET    | /layout/{layoutId}/tabular               | Get tabular view of the layout |

## Swagger Documentation

[https://integer-spiral-api.guvensen.com/swagger](https://integer-spiral-api.guvensen.com/swagger)
