create or replace table order_item
(
    order_id   bigint unsigned not null,
    book_id    bigint unsigned not null,
    quantity   int unsigned    not null,
    unit_price bigint unsigned not null,
    comment    varchar(255)    null,
    primary key (order_id, book_id),
    constraint order_item_ibfk_1
        foreign key (order_id) references `order` (id),
    constraint order_item_ibfk_2
        foreign key (book_id) references book (id)
);

create or replace index order_id
    on order_item (order_id);

create or replace index product_id
    on order_item (book_id);

