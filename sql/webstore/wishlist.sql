create or replace table wishlist
(
    book_id bigint unsigned not null,
    user_id bigint unsigned not null,
    primary key (book_id, user_id),
    constraint wishlist_ibfk_1
        foreign key (book_id) references book (id),
    constraint wishlist_ibfk_2
        foreign key (user_id) references user (id)
);

create or replace index user_id
    on wishlist (user_id);

