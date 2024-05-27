create table nmeadata_minute
(
    lfd      int auto_increment primary key,
    time     datetime null,
    phg      int      null,
    canIdHex text     null,
    Data     text     null
);
create table nmeadata_hour
(
    lfd      int auto_increment primary key,
    time     datetime null,
    phg      int      null,
    canIdHex text     null,
    Data     text     null
);
create table nmeadata_day
(
    lfd      int auto_increment primary key,
    time     datetime null,
    phg      int      null,
    canIdHex text     null,
    Data     text     null
);
create table nmeadata_week
(
    lfd      int auto_increment primary key,
    time     datetime null,
    phg      int      null,
    canIdHex text     null,
    Data     text     null
);
create table nmeadata_month
(
    lfd      int auto_increment primary key,
    time     datetime null,
    phg      int      null,
    canIdHex text     null,
    Data     text     null
);