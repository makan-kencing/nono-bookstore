create or replace table invoice
(
    id          bigint unsigned auto_increment
        primary key,
    order_id    bigint unsigned                       not null,
    invoiced_at timestamp default current_timestamp() not null,
    constraint invoice_ibfk_1
        foreign key (order_id) references `order` (id)
);

create or replace index order_id
    on invoice (order_id);

