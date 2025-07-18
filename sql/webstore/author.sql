create or replace table author
(
    id          bigint unsigned auto_increment
        primary key,
    slug        varchar(255) not null,
    name        varchar(255) not null,
    description text         not null,
    constraint id
        unique (id),
    constraint name
        unique (name),
    constraint slug
        unique (slug)
);

