create database integer_spiral;

CREATE TABLE public.integer_layout
(
    id SERIAL CONSTRAINT id PRIMARY KEY,
    value TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);