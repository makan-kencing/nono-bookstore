create or replace table book_image
(
    book_id     bigint unsigned   not null,
    file_id     bigint unsigned   not null,
    image_order smallint unsigned not null,
    primary key (book_id, file_id),
    constraint book_id
        unique (book_id, image_order),
    constraint book_image_ibfk_1
        foreign key (book_id) references book (id),
    constraint book_image_ibfk_2
        foreign key (file_id) references file (id)
);

create or replace index file_id
    on book_image (file_id);

