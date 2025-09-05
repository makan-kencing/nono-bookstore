create or replace table author
(
    id          bigint unsigned auto_increment
        primary key,
    slug        varchar(255)    not null,
    name        varchar(255)    not null,
    description text            null,
    image_id    bigint unsigned null,
    constraint id
        unique (id),
    constraint slug
        unique (slug),
    constraint author_ibfk_1
        foreign key (image_id) references file (id)
);

create or replace index image_id
    on author (image_id);

