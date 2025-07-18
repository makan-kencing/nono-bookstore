create or replace table payment
(
    id         bigint unsigned auto_increment
        primary key,
    invoice_id bigint unsigned                       not null,
    ref_no     varchar(255)                          not null,
    method     varchar(31)                           not null,
    amount     bigint unsigned                       not null,
    comment    varchar(255)                          null,
    paid_at    timestamp default current_timestamp() not null,
    constraint payment_ibfk_1
        foreign key (invoice_id) references invoice (id)
);

create or replace index invoice_id
    on payment (invoice_id);

