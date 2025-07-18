create or replace table inventory
(
    product_id bigint unsigned not null
        primary key,
    quantity   int unsigned    not null,
    constraint inventory_ibfk_1
        foreign key (product_id) references product (id)
);

