create or replace table address
(
    id           bigint unsigned auto_increment
        primary key,
    user_id      bigint unsigned not null,
    name         varchar(255)    not null,
    address1     varchar(255)    not null,
    address2     varchar(255)    null,
    address3     varchar(255)    null,
    state        varchar(255)    not null,
    postcode     varchar(255)    not null,
    country      varchar(255)    not null,
    phone_number varchar(20)     not null,
    constraint address_ibfk_1
        foreign key (user_id) references user (id)
);

create or replace index user_id
    on address (user_id);

