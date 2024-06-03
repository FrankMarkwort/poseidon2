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
- 3 http://127.0.0.1/service.phtml\
start Migration \
![Screenshot_20240603_062101](https://github.com/FrankMarkwort/poseidon2/assets/78704564/c92eaca4-0568-4ad2-be03-6e33044d3fb0)




**Display of the decoded data.**\
The data is currently displayed in the browser as HTML or JSON.\
The Apache directory is _src/http/_ \

**.htaccess**\
- SetEnv RUN_MODE production
