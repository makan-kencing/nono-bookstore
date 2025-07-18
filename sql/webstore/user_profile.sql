create or replace table user_profile
(
    user_id    bigint unsigned not null
        primary key,
    contact_no varchar(255)    null,
    dob        date            null,
    constraint user_profile_ibfk_1
        foreign key (user_id) references user (id)
);

