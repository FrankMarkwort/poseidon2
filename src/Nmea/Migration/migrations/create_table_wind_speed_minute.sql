use nmea2000;
CREATE SEQUENCE wind_speed_minute_seq START WITH 1 INCREMENT BY 1 MINVALUE=1 MAXVALUE=60 CYCLE;
create table wind_speed_minute
(
    id_minute    int default nextval(`nmea2000`.`wind_speed_minute_seq`) not null primary key,
    timestamp    time                                                   not null,
    windSpeed    double                                                  not null,
    windAngle    double                                                  not null,
    windRefernce varchar(20)                                             not null,
    COGReference varchar(10)                                             not null,
    COG          double                                                  not null,
    SOG          double                                                  not null
);

create table wind_speed_hour
(
    date         datetime default current_timestamp() not null
        primary key,
    avgWindSpeed double                               not null,
    maxWindSpeed double                               not null,
    minWindSpeed double                               not null,
    avgWindAngle double                               not null,
    maxWindAngle double                               not null,
    minWindAngle double                               not null,
    avgCOG       double                               not null,
    maxCOG       double                               not null,
    minCOG       double                               not null,
    avgSOG       double                               not null,
    maxSOG       double                               not null,
    minSOG       double                               not null
);

create definer = nmea2000@`%` trigger generateHourStatistics
    before insert
    on wind_speed_minute
    for each row
BEGIN
       IF NEW.id_minute = 60 THEN
            insert into wind_speed_hour(
                avgWindSpeed, maxWindSpeed, minWindspeed,
                avgWindAngle, maxWindAngle, minWindAngle,
                avgCOG, maxCOG, minCOG,
                avgSOG, maxSOG, minSOG
            )
            select
                avg(windSpeed) as avgWindSpeed, max(windSpeed)  as maxWindSpeed, min(windSpeed) as minWindspeed,
                avg(windAngle) as avgWindAngle,  max(windAngle) as maxWindAngle, min(windAngle) as minWindAngle,
                avg(COG) as avgCOG,  max(COG) as maxCOG, min(COG) as minCOG,
                avg(SOG) as avgSOG,  max(SOG) as maxSOG, min(SOG) as minSOG
            from wind_speed_minute;
        END IF;
    END;
