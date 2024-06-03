/** \
@author Frank Markwort\
@version 0.1.0\
@email frank.markwort@gmail.com\
*/\
The decoder works on 32 bit and 64 bit systems\
\
The deamon assembles the data packets and saves them ready for decoding in the memcached server. The key is the pgn.\
Data format from usb device\
cat /dev/ttyACM0 \
06:26:23.548 R 09FD0270 00 0C 02 11 AA FA FF FF\
\
**Deamon**\
_Dependencies and configuration_
PHP 8.3, memcached and mariadb.
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
![Screenshot_20240603_062101](https://github.com/FrankMarkwort/poseidon2/assets/78704564/c92eaca4-0568-4ad2-be03-6e33044d3fb0)
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
**Display of the decoded data.**\
The data is currently displayed in the browser as HTML or JSON.\
The Apache directory is _src/http/_ \

