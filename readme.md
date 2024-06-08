/** \
@author Frank Markwort\
@version 0.9.2\
@email frank.markwort@gmail.com\
*/\
The wind data is stored every minute for maximum 60 minutes.\
(Id, Date, AWA, AWS, TWS, TWA, TWD, VesselHeading, SOG, COG, waterTemperature)\
After that, the average, min, max values ​​are stored in the hourly table.\
The ship's position is stored in the position table every hour when the ship is moving.\
(Id, Id_wind, Date, Latitude, longitude)\
***The decoder works on 32 bit and 64 bit systems***\
\
The deamon assembles the data packets from "NMEA 2000 USB Gateway YDNU-02 (Yacht-Device)" and saves them ready for decoding in the memcached server. The key is the pgn.\
Data format from usb device
```
root@raspberrypi:/etc/apache2/sites-enabled# cat /dev/ttyACM0

06:13:27.186 R 09F5036E 00 00 00 FF FF 00 FF FF

06:13:27.187 R 19F5136E 80 0E FF FF FF FF FF FF

06:13:27.188 R 15FD066E 00 A5 73 FF FF FF FF FF

06:13:27.189 R 15FD086E 00 00 00 A5 73 FF FF FF
06:13:27.205 R 09F80200 FF FC 7C F1 0B 00 FF FF
06:13:27.205 R 09F80100 37 7F 21 16 5C B7 00 10
06:13:27.206 R 0DF90B00 FF FC 67 00 0C 00 FF FF
06:13:27.207 R 19F81400 80 14 57 38 34 00 00 00
```
**Deamon**\
_Dependencies_
PHP 8.3, memcached and mariadb.\
**configuration**
- 1 Create a user nmea2000 in the database.
- 2 In the configuration file src/config/config.php under the 'production' section, enter the memcached host and port, \
the database host and port.
```
  ],
    'production' => [
        'memcached' => [
            'host' => '127.0.0.1',
            'port' => 11211
        ],
        'mariadb' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'nmea2000',
            'password' => 'nmea2000',
            'dbname' => 'nmea2000'
        ],
        'serialdevice' => '/dev/ttyACM0',
    ],
    'testing' => [
```
- 3 Create database tables \
  http://127.0.0.1/service.phtml => Run DB-Migration \
![Screenshot_20240603_094645](https://github.com/FrankMarkwort/poseidon2/assets/78704564/20989006-c8cd-4fc9-8d62-c76d3a09b841)
- 4 Create Linux service phpreader\
```root@raspberrypi:~# cat /etc/systemd/system/phpreader.service
[Unit]
Description=Data NMEA2000 Reader

[Service]
Type=simple
ExecStart=/usr/bin/php  /var/www/html/src/http/../deamon/deamon.php
Restart=always

[Install]
WantedBy=multi-user.target
```
- 5 Create Linux service phpcron\
```root@raspberrypi:~# cat /etc/systemd/system/phpcron.service 
[Unit]
Description=Data NMEA2000 Cron

[Service]
Type=simple
ExecStart=/usr/bin/php  /var/www/html/src/http/../deamon/cron.php
Restart=always

[Install]
WantedBy=multi-user.target
```
- 6 activate the services
  ```
  sudo systemctl enable phpreader
  sudo systemctl enable phpcron
  ```
- 7 start and test
```
root@raspberrypi:~# service phpreader start
root@raspberrypi:~# service phpcron start
root@raspberrypi:~# service phpreader status
● phpreader.service - Data NMEA2000 Reader
   Loaded: loaded (/etc/systemd/system/phpreader.service; enabled; vendor preset: enabled)
   Active: active (running) since Mon 2024-06-03 07:45:50 CEST; 3min 25s ago
 Main PID: 9627 (php)
   CGroup: /system.slice/phpreader.service
           └─9627 /usr/bin/php /var/www/html/src/http/../deamon/deamon.php

Jun 03 07:45:50 raspberrypi systemd[1]: Started Data NMEA2000 Reader.
root@raspberrypi:~# service phpcrom status
Unit phpcrom.service could not be found.
root@raspberrypi:~# service phpcron status
● phpcron.service - Data NMEA2000 Cron
   Loaded: loaded (/etc/systemd/system/phpcron.service; enabled; vendor preset: enabled)
   Active: active (running) since Mon 2024-06-03 04:10:03 CEST; 3h 39min ago
 Main PID: 7279 (php)
   CGroup: /system.slice/phpcron.service
           └─7279 /usr/bin/php /var/www/html/src/http/../deamon/cron.php

Jun 03 04:10:03 raspberrypi systemd[1]: Started Data NMEA2000 Cron.
```
- 8 In the .htaccess set the RUN_MODE to production
```
root@raspberrypi:/var/www/html/src/http# cat .htaccess
SetEnv RUN_MODE production
<FilesMatch "\.ph(p[2-6]?|tml)$">
    SetHandler application/x-httpd-php

</FilesMatch>
```
- 9 Apache configuration something like this
```
root@raspberrypi:/etc/apache2/sites-enabled# cat  000-default.conf 
VirtualHost *:80>
 ServerAdmin webmaster@dummy-host.example.com
    ServerName localhost 
    DocumentRoot  /var/www/html/src/http  
    ErrorLog /var/log/apache2/poseidon.localnet-error_log
    CustomLog /var/log/apache2/podeidon.localnet-access_log combined
    AllowEncodedSlashes On
    <Directory "/var/www/html/src/http">
        AllowOverride all
        # New directive needed in Apache 2.4.3:
        Require all granted    
    </Directory>
    <IfModule mod_rewrite.c>
       RewriteEngine On
       Options +FollowSymLinks
    </IfModule>
</VirtualHost>
```
**Displaying the decoded data.**\
The data is currently displayed in the browser as HTML or JSON.\
```
as html http://127.0.0.1/index.phtml
as Json http://127.0.0.1/index.phtml?mode=json
as Json only one pgn http://127.0.0.1/index.phtml?mode=json&pgn=129291\
as Highchart http://127.0.0.1/graph.phtml
Services http://127.0.0.1/service.phtml __see point 3__
```
**src/deamon/cron.php**\
```
pi@raspberrypi:/var/www/html/src/deamon $ ./cron.php --help
--help
--version
--mode= [debug|both]
     {debug} only print to terminal
     {both}  run normal and print to terminal

pi@raspberrypi:/var/www/html/src/deamon $ ./cron.php --mode=both
mode=both
Temperature pgn => 130312 src => 110 dst => 255 type => SP pduFormat => 253 dataPage => 1
1, SID 1, 0 1, Integer
2, Instance 2, 0 2, Integer
3, Source 3, Sea Temperature 3, Lookup table
4, Actual Temperature 4, 296.15 4, Temperature
5, Set Temperature 5,  5, Temperature
6, Reserved 6, 255 6, 
Wind Data pgn => 130306 src => 112 dst => 255 type => SP pduFormat => 253 dataPage => 1
1, SID 1, 0 1, Integer
2, Wind Speed 2, 3.44 2, Number
3, Wind Angle 3, 4.7376 3, Number
4, Reference 4, Apparent 4, Lookup table
5, Reserved 5, 2097151 5, 
COG & SOG, Rapid Update pgn => 129026 src => 0 dst => 255 type => SP pduFormat => 248 dataPage => 1
1, SID 1,  1, Integer
2, COG Reference 2, True 2, Lookup table
3, Reserved 3, 63 3, 
4, COG 4, 0.3211 4, Number
5, SOG 5, 0.09 5, Number
6, Reserved 6, 65535 6, 
Vessel Heading pgn => 127250 src => 115 dst => 255 type => SP pduFormat => 241 dataPage => 1
1, SID 1, 0 1, Integer
2, Heading 2, 1.2397 2, Number
3, Deviation 3,  3, Number
4, Variation 4,  4, Number
5, Reference 5, Magnetic 5, Lookup table
6, Reserved 6, 63 6, 

store wind minute data !

Temperature pgn => 130312 src => 110 dst => 255 type => SP pduFormat => 253 dataPage => 1
1, SID 1, 0 1, Integer
2, Instance 2, 0 2, Integer
3, Source 3, Sea Temperature 3, Lookup table
4, Actual Temperature 4, 296.05 4, Temp    
```
