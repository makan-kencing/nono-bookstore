create or replace table price
(
    id        bigint unsigned auto_increment
        primary key,
    book_id   bigint unsigned not null,
    from_date datetime        null,
    thru_date datetime        null,
    amount    bigint unsigned null,
    comment   varchar(255)    null,
    constraint price_ibfk_2
        foreign key (book_id) references book (id)
);

create or replace index product_id
    on price (book_id);

