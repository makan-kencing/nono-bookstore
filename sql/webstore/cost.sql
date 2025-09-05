create or replace table cost
(
    id        bigint unsigned auto_increment
        primary key,
    book_id   bigint unsigned not null,
    amount    bigint unsigned not null,
    from_date datetime        not null,
    thru_date datetime        null,
    comment   varchar(255)    null,
    constraint cost_ibfk_1
        foreign key (book_id) references book (id)
);

create or replace index product_id
    on cost (book_id);

