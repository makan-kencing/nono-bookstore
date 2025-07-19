create or replace table series_author_definition
(
    series_id bigint unsigned                               not null,
    author_id bigint unsigned                               not null,
    type      enum ('AUTHOR', 'ILLUSTRATOR', 'CONTRIBUTOR') not null,
    comment   varchar(255)                                  null,
    primary key (series_id, author_id),
    constraint series_author_definition_ibfk_1
        foreign key (series_id) references series (id),
    constraint series_author_definition_ibfk_2
        foreign key (author_id) references author (id)
);

create or replace index author_id
    on series_author_definition (author_id);

