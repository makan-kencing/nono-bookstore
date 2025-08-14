create or replace table cost
(
    id         bigint unsigned auto_increment
        primary key,
    product_id bigint unsigned not null,
    amount     bigint unsigned not null,
    from_date  datetime        not null,
    thru_date  datetime        null,
    comment    varchar(255)    null,
    constraint cost_ibfk_1
        foreign key (product_id) references product (id)
);

create or replace index product_id
    on cost (product_id);

