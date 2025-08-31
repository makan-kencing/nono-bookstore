create or replace table order_adjustment
(
    id       bigint unsigned auto_increment
        primary key,
    order_id bigint unsigned                                   not null,
    type     enum ('TAX', 'SHIPPING', 'DISCOUNT', 'SURCHARGE') not null,
    amount   bigint unsigned                                   null,
    comment  varchar(255)                                      null,
    constraint id
        unique (id),
    constraint order_adjustment_ibfk_1
        foreign key (order_id) references `order` (id)
);

create or replace index order_id
    on order_adjustment (order_id);

