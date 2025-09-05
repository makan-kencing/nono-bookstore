create or replace table author_definition
(
    book_id   bigint unsigned                                                       not null,
    author_id bigint unsigned                                                       not null,
    type      enum ('AUTHOR', 'ILLUSTRATOR', 'CONTRIBUTOR', 'EDITOR', 'TRANSLATOR') null,
    comment   varchar(255)                                                          null,
    primary key (book_id, author_id),
    constraint author_definition_ibfk_1
        foreign key (book_id) references book (id),
    constraint author_definition_ibfk_2
        foreign key (author_id) references author (id)
);

create or replace index author_id
    on author_definition (author_id);

