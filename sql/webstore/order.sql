create or replace table `order`
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned                       not null,
    address_id bigint unsigned                       not null,
    ref_no     varchar(255)                          not null,
    ordered_at timestamp default current_timestamp() not null,
    constraint order_address_id_fk
        foreign key (address_id) references address (id),
    constraint order_user_id_fk
        foreign key (user_id) references user (id)
);

