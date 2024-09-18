create table positions
(
    id                  int auto_increment primary key,
    fid_wind_speed_hour int                                  not null,
    timestamp           datetime default current_timestamp() not null,
    latitude            double                               not null,
    longitude           double                               not null,
    cog                 double   default 0                   not null,
    sog                 double   default 0                   not null,
    `set`               double   default 0                   not null,
    drift               double   default 0                   not null,
    constraint positions_wind_speed_hour_id_fk
        foreign key (fid_wind_speed_hour) references wind_speed_hour (id)
            on update cascade on delete cascade
)
    engine = InnoDB;