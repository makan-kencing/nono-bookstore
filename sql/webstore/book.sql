create or replace table book
(
    id              bigint unsigned auto_increment
        primary key,
    slug            varchar(255)    not null,
    isbn            char(13)        not null,
    title           varchar(255)    not null,
    description     text            null,
    publisher       varchar(255)    not null,
    published_date  date            not null,
    number_of_pages int             not null,
    series_id       bigint unsigned null,
    language        varchar(255)    null,
    dimensions      varchar(255)    null,
    constraint isbn
        unique (isbn),
    constraint slug
        unique (slug),
    constraint book_ibfk_1
        foreign key (series_id) references series (id)
);

create or replace index series_id
    on book (series_id);

