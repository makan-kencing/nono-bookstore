create or replace table series_definition
(
    book_id      bigint unsigned   not null
        primary key,
    series_id    bigint unsigned   not null,
    position     varchar(100)      not null,
    series_order smallint unsigned not null,
    constraint book_id
        unique (book_id, series_order),
    constraint series_definition_ibfk_1
        foreign key (book_id) references book (id),
    constraint series_definition_ibfk_2
        foreign key (series_id) references series (id)
);

create or replace index series_id
    on series_definition (series_id);

