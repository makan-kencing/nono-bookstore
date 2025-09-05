create or replace table category_definition
(
    work_id     bigint unsigned                      not null,
    category_id bigint unsigned                      not null,
    is_primary  bit      default b'0'                not null,
    comment     varchar(255)                         null,
    from_date   datetime default current_timestamp() not null,
    thru_date   datetime                             null,
    primary key (work_id, category_id),
    constraint category_definition_ibfk_2
        foreign key (category_id) references category (id),
    constraint category_definition_ibfk_3
        foreign key (work_id) references work (id)
);

create or replace index category_id
    on category_definition (category_id);

