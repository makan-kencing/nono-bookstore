create or replace table price_definition
(
    id          bigint unsigned auto_increment
        primary key,
    type        enum ('BASE', 'DISCOUNT', 'SURCHARGE') not null,
    book_id     bigint unsigned                        null,
    product_id  bigint unsigned                        null,
    author_id   bigint unsigned                        null,
    category_id bigint unsigned                        null,
    from_date   datetime                               null,
    thru_date   datetime                               null,
    amount      bigint unsigned                        null,
    percentage  smallint unsigned                      null,
    comment     varchar(255)                           null,
    constraint price_definition_ibfk_1
        foreign key (book_id) references book (id),
    constraint price_definition_ibfk_2
        foreign key (product_id) references product (id),
    constraint price_definition_ibfk_3
        foreign key (author_id) references author (id),
    constraint price_definition_ibfk_4
        foreign key (category_id) references category (id),
    check (`amount` is null and `percentage` is not null or `amount` is not null and `percentage` is null)
);

create or replace index author_id
    on price_definition (author_id);

create or replace index book_id
    on price_definition (book_id);

create or replace index category_id
    on price_definition (category_id);

create or replace index product_id
    on price_definition (product_id);

