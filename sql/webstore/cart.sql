create or replace table cart
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned null,
    address_id bigint unsigned null,
    constraint user_id
        unique (user_id),
    constraint cart_address_id_fk
        foreign key (address_id) references address (id),
    constraint cart_ibfk_1
        foreign key (user_id) references user (id)
);

