create or replace table shipment
(
    id         bigint unsigned auto_increment
        primary key,
    order_id   bigint unsigned                       not null,
    ready_at   datetime                              null,
    shipped_at datetime                              null,
    arrived_at datetime                              null,
    updated_at timestamp default current_timestamp() not null on update current_timestamp(),
    constraint shipment_ibfk_1
        foreign key (order_id) references `order` (id)
);

create or replace index order_id
    on shipment (order_id);

