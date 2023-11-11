create database integer_spiral;

CREATE TABLE public.integer_layout
(
    id SERIAL CONSTRAINT id PRIMARY KEY,
    value JSON,
    x INTEGER NOT NULL ,
    y INTEGER NOT NULL ,
    created_at TIMESTAMP DEFAULT NOW()
);