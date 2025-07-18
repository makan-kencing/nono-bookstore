create or replace table book_image
(
    id        bigint unsigned auto_increment
        primary key,
    book_id   bigint unsigned not null,
    image_url varchar(255)    not null,
    constraint book_image_ibfk_1
        foreign key (book_id) references book (id)
);

create or replace index book_id
    on book_image (book_id);

