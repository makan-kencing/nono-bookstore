create or replace table book
(
    id                  bigint unsigned auto_increment
        primary key,
    work_id             bigint unsigned                 not null,
    isbn                char(13)                        not null,
    description         text                            null,
    publisher           varchar(255)                    not null,
    publication_date    varchar(20)                     not null,
    number_of_pages     int                             not null,
    cover_type          enum ('PAPERBACK', 'HARDCOVER') null,
    edition_information varchar(255)                    null,
    language            varchar(255)                    null,
    dimensions          varchar(255)                    null,
    deleted_at          timestamp                       null,
    constraint isbn
        unique (isbn),
    constraint book_work_id_fk
        foreign key (work_id) references work (id)
);

