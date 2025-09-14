create or replace table user
(
    id                 bigint unsigned auto_increment
        primary key,
    username           varchar(255)                                            not null,
    email              varchar(255)                                            not null,
    hashed_password    varchar(255)                                            not null,
    role               enum ('USER', 'STAFF', 'ADMIN', 'OWNER') default 'USER' not null,
    is_verified        bit                                      default b'0'   not null,
    is_blocked         bit                                      default b'0'   not null,
    totp_secret        char(103)                                               null,
    session_flag       uuid                                     default uuid() not null,
    image_id           bigint unsigned                                         null,
    default_address_id bigint unsigned                                         null,
    constraint email
        unique (email),
    constraint session_flag
        unique (session_flag),
    constraint username
        unique (username),
    constraint user_ibfk_1
        foreign key (image_id) references file (id),
    constraint user_ibfk_2
        foreign key (default_address_id) references address (id)
);

create or replace index default_address_id
    on user (default_address_id);

create or replace index image_id
    on user (image_id);

