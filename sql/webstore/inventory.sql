create or replace table inventory
(
    id       bigint unsigned auto_increment
        primary key,
    book_id  bigint unsigned                        not null,
    location enum ('WAREHOUSE', 'OFFSITE', 'STORE') not null,
    quantity int unsigned                           not null,
    constraint inventory_ibfk_1
        foreign key (book_id) references book (id)
);

create or replace index product_id
    on inventory (book_id);

