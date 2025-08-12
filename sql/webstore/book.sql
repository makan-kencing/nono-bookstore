create or replace table book
(
    id              bigint unsigned auto_increment
        primary key,
    slug            varchar(255)    not null,
    isbn            char(13)        not null,
    title           varchar(255)    not null,
    description     text            null,
    publisher_id    bigint unsigned not null,
    published_date  varchar(20)     not null,
    number_of_pages int             not null,
    language        varchar(255)    null,
    dimensions      varchar(255)    null,
    constraint isbn
        unique (isbn),
    constraint slug
        unique (slug),
    constraint book_ibfk_2
        foreign key (publisher_id) references publisher (id)
);

create or replace index publisher_id
    on book (publisher_id);

