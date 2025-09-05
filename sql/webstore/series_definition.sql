create or replace table series_definition
(
    work_id   bigint unsigned   not null,
    series_id bigint unsigned   not null,
    position  smallint unsigned not null,
    primary key (work_id, series_id),
    constraint work_id
        unique (work_id, position),
    constraint series_definition_ibfk_2
        foreign key (series_id) references series (id),
    constraint series_definition_ibfk_3
        foreign key (work_id) references work (id)
);

create or replace index series_id
    on series_definition (series_id);

