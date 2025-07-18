create or replace table book
(
    id              bigint unsigned auto_increment
        primary key,
    slug            varchar(255) not null,
    isbn            char(13)     not null,
    title           varchar(255) not null,
    description     text         not null,
    publisher       varchar(255) not null,
    published_date  datetime     not null,
    number_of_pages int          not null,
    language        varchar(255) null,
    dimensions      varchar(255) null,
    constraint isbn
        unique (isbn),
    constraint slug
        unique (slug)
);

