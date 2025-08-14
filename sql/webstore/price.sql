create or replace table price
(
    id         bigint unsigned auto_increment
        primary key,
    product_id bigint unsigned not null,
    from_date  datetime        null,
    thru_date  datetime        null,
    amount     bigint unsigned null,
    comment    varchar(255)    null,
    constraint price_ibfk_2
        foreign key (product_id) references product (id)
);

create or replace index product_id
    on price (product_id);

