create or replace table book
(
    id              bigint unsigned auto_increment
        primary key,
    slug            varchar(255)     not null,
    isbn            char(13)         not null,
    title           varchar(255)     not null,
    description     text             null,
    publisher_id    bigint unsigned  not null,
    published_date  date             not null,
    number_of_pages int              not null,
    series_id       bigint unsigned  null,
    series_position tinyint unsigned null,
    language        varchar(255)     null,
    dimensions      varchar(255)     null,
    constraint isbn
        unique (isbn),
    constraint slug
        unique (slug),
    constraint book_ibfk_1
        foreign key (series_id) references series (id),
    constraint book_ibfk_2
        foreign key (publisher_id) references publisher (id)
);

create or replace index publisher_id
    on book (publisher_id);

create or replace index series_id
    on book (series_id);

