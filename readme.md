/** \
@author Frank Markwort\
@version 0.1.0\
@email frank.markwort@gmail.com\
*/\
The decoder works on 32 bit and 64 bit systems\
\
The deamon assembles the data packets and saves them ready for decoding in the memcached server. The key is the pgn.\
Data format from usb device\
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
  http://127.0.0.1/service.phtml \
start Migration \
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
- 5 Create Linux service phpcrom\
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
Services http://127.0.0.1/service.phtml
```

