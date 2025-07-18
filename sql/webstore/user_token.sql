create or replace table user_token
(
    id         bigint unsigned auto_increment
        primary key,
    user_id    bigint unsigned                                         not null,
    type       enum ('REMEMBER_ME', 'RESET_PASSWORD', 'CONFIRM_EMAIL') not null,
    selector   char(12)                                                not null,
    token      char(64)                                                not null,
    expires_at timestamp                                               not null,
    constraint user_token_ibfk_1
        foreign key (user_id) references user (id)
);

create or replace index user_id
    on user_token (user_id);

create or replace index user_token_type_selector_index
    on user_token (type, selector);

