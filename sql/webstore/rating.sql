create or replace table rating
(
    id      bigint unsigned auto_increment
        primary key,
    book_id bigint unsigned      not null,
    user_id bigint unsigned      not null,
    rating  smallint(6) unsigned not null,
    title   varchar(255)         not null,
    content text                 not null,
    constraint rating_ibfk_1
        foreign key (book_id) references book (id),
    constraint rating_ibfk_2
        foreign key (user_id) references user (id),
    check (`rating` > 0 and `rating` <= 10)
);

create or replace index book_id
    on rating (book_id);

create or replace index user_id
    on rating (user_id);

