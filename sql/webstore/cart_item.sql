create or replace table cart_item
(
    cart_id  bigint unsigned not null,
    book_id  bigint unsigned not null,
    quantity int unsigned    not null,
    primary key (cart_id, book_id),
    constraint cart_item_ibfk_1
        foreign key (cart_id) references cart (id),
    constraint cart_item_ibfk_2
        foreign key (book_id) references book (id)
);

create or replace index product_id
    on cart_item (book_id);

