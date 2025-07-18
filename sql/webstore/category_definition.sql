create or replace table category_definition
(
    book_id     bigint unsigned  not null,
    category_id bigint unsigned  not null,
    is_primary  bit default b'0' not null,
    comment     varchar(255)     null,
    from_date   datetime         not null,
    thru_date   datetime         null,
    primary key (book_id, category_id),
    constraint category_definition_ibfk_1
        foreign key (book_id) references book (id),
    constraint category_definition_ibfk_2
        foreign key (category_id) references category (id)
);

create or replace index category_id
    on category_definition (category_id);

