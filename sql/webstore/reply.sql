create or replace table reply
(
    id        bigint unsigned auto_increment
        primary key,
    rating_id bigint unsigned not null,
    user_id   bigint unsigned not null,
    content   text            not null,
    constraint reply_ibfk_1
        foreign key (rating_id) references rating (id),
    constraint reply_ibfk_2
        foreign key (user_id) references user (id)
);

create or replace index rating_id
    on reply (rating_id);

create or replace index user_id
    on reply (user_id);

