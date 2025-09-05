create or replace table rating
(
    id       bigint unsigned auto_increment
        primary key,
    work_id  bigint unsigned                      not null,
    user_id  bigint unsigned                      not null,
    rating   smallint(6) unsigned                 not null,
    title    varchar(255)                         not null,
    content  text                                 not null,
    rated_at datetime default current_timestamp() not null,
    constraint rating_ibfk_1
        foreign key (work_id) references work (id),
    constraint rating_ibfk_2
        foreign key (user_id) references user (id),
    check (`rating` > 0 and `rating` <= 10)
);

create or replace index book_id
    on rating (work_id);

create or replace index user_id
    on rating (user_id);

