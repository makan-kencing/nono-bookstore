create or replace table file
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned                       not null,
    filename   varchar(255)                          not null,
    mimetype   varchar(255)                          not null,
    alt        varchar(255)                          null,
    filepath   varchar(255)                          not null,
    created_at timestamp default current_timestamp() not null,
    constraint file_ibfk_1
        foreign key (user_id) references user (id)
);

create or replace index created_by
    on file (user_id);

