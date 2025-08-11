create or replace table publisher
(
    id   bigint unsigned auto_increment
        primary key,
    slug varchar(255) not null,
    name varchar(255) not null,
    constraint slug
        unique (slug)
);

