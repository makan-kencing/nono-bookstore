create or replace table user_security_event
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned                                                                             not null,
    event      enum ('SUCCESSFUL_LOGIN', 'ATTEMPTED_LOGIN', 'LOGOUT', 'CHANGED_PASSWORD', 'CHANGED_EMAIL') not null,
    data       longtext collate utf8mb4_bin                                                                not null
        check (json_valid(`data`)),
    created_at timestamp default current_timestamp()                                                       not null,
    constraint user_security_event_ibfk_1
        foreign key (user_id) references user (id)
);

create or replace index user_id
    on user_security_event (user_id);

