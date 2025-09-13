create or replace table work
(
    id                 bigint unsigned auto_increment
        primary key,
    slug               varchar(255)    not null,
    title              varchar(255)    not null,
    author_id          bigint unsigned null,
    default_edition_id bigint unsigned null,
    constraint work_ibfk_1
        foreign key (author_id) references author (id),
    constraint work_ibfk_2
        foreign key (default_edition_id) references book (id)
);

create or replace index author_id
    on work (author_id);

create or replace index default_edition_id
    on work (default_edition_id);

