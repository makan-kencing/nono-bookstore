create or replace table order_adjustment
(
    id         bigint unsigned auto_increment
        primary key,
    order_id   bigint unsigned                                   not null,
    item_id    bigint unsigned                                   null,
    type       enum ('TAX', 'SHIPPING', 'DISCOUNT', 'SURCHARGE') not null,
    amount     bigint unsigned                                   null,
    percentage smallint unsigned                                 null,
    comment    varchar(255)                                      null,
    constraint id
        unique (id),
    constraint order_adjustment_ibfk_1
        foreign key (order_id) references `order` (id),
    constraint order_adjustment_ibfk_2
        foreign key (item_id) references order_item (id),
    check (`amount` is null and `percentage` is not null or `amount` is not null and `percentage` is null)
);

create or replace index item_id
    on order_adjustment (item_id);

create or replace index order_id
    on order_adjustment (order_id);

