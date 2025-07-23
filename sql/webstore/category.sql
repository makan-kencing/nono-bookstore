create or replace table category
(
    id          bigint unsigned auto_increment
        primary key,
    slug        varchar(255)    not null,
    name        varchar(255)    not null,
    description text            null,
    parent_id   bigint unsigned null,
    constraint name
        unique (name),
    constraint slug
        unique (slug),
    constraint category_category_id_fk
        foreign key (parent_id) references category (id)
);

