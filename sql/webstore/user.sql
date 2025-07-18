create or replace table user
(
    id              bigint unsigned auto_increment
        primary key,
    username        varchar(255)                                            not null,
    email           varchar(255)                                            not null,
    hashed_password varchar(255)                                            not null,
    role            enum ('USER', 'STAFF', 'ADMIN', 'OWNER') default 'USER' not null,
    is_verified     bit                                      default b'0'   not null,
    constraint email
        unique (email),
    constraint username
        unique (username)
);

