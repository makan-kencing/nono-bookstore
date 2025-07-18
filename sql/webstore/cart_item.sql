create or replace table cart_item
(
    cart_id    bigint unsigned not null,
    product_id bigint unsigned not null,
    quantity   int unsigned    not null,
    primary key (cart_id, product_id),
    constraint cart_item_ibfk_1
        foreign key (cart_id) references cart (id),
    constraint cart_item_ibfk_2
        foreign key (product_id) references product (id)
);

create or replace index product_id
    on cart_item (product_id);

