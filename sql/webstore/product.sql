create or replace table product
(
    id         bigint unsigned auto_increment
        primary key,
    book_id    bigint unsigned                 not null,
    cover_type enum ('PAPERBACK', 'HARDCOVER') null,
    from_date  datetime                        not null,
    thru_date  datetime                        null,
    constraint product_ibfk_1
        foreign key (book_id) references book (id)
);

create or replace index book_id
    on product (book_id);

