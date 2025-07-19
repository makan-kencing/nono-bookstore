create or replace table series
(
    id          bigint unsigned auto_increment
        primary key,
    slug        varchar(255) not null,
    name        varchar(255) not null,
    description text         not null,
    constraint slug
        unique (slug)
);

