create or replace table default_address
(
    user_id    bigint unsigned not null
        primary key,
    address_id bigint unsigned not null,
    constraint default_address_address_id_fk
        foreign key (address_id) references address (id),
    constraint default_address_user_id_fk
        foreign key (user_id) references user (id)
);

