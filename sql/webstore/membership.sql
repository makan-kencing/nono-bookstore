create or replace table membership
(
    user_id   bigint unsigned not null
        primary key,
    card_no   varchar(255)    not null,
    from_date datetime        not null,
    thru_date datetime        null,
    constraint card_no
        unique (card_no),
    constraint membership_ibfk_1
        foreign key (user_id) references user (id)
);

