create or replace table inventory
(
    id         bigint unsigned auto_increment
        primary key,
    product_id bigint unsigned                        not null,
    location   enum ('WAREHOUSE', 'OFFSITE', 'STORE') not null,
    quantity   int unsigned                           not null,
    constraint inventory_ibfk_1
        foreign key (product_id) references product (id)
);

create or replace index product_id
    on inventory (product_id);

