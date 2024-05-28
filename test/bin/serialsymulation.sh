#!/bin/bash
#socat PTY,link=./ttyIn,raw,echo=0 PTY,link=/srv/poseidon/poseidon2/tests/dev/ttyOut,raw,echo=0
#socat tcp-listen:12345 PTY,link=./ttyOut,raw,echo=0
#socat TCP-LISTEN:1234,bind=127.0.0.1,reuseaddr,fork,su=nobody,range=127.0.0.0/8 UNIX-CLIENT:/tmp/foo
#socat TCP-LISTEN:1235,bind=127.0.0.1,reuseaddr,fork,range=127.0.0.0/8 UNIX-CLIENT:/srv/poseidon/poseidon2/tests/dev/foo
#socat TCP-LISTEN:1235,bind=127.0.0.1,reuseaddr,fork,range=127.0.0.0/8 link=/srv/poseidon/poseidon2/tests/dev/ttyOut,creat,append
#socat TCP-LISTEN:1235,bind=172.17.0.2,reuseaddr,fork PTY,link=../../dev/ttyOut,raw,echo=0
socat TCP-LISTEN:1235,bind=172.17.0.2,reuseaddr,fork PTY,link=./ttyOut,raw,echo=0
socat PTY,link=/dev/ttyACM0,raw,echo=0,fork TCP-LISTEN:1235
socat /dev/ttyACM0,raw,echo=0,fork STDOUT
socat TCP-LISTEN:1235,bind=172.17.0.2,reuseaddr,fork STDOUT
//Rasperiy
socat /dev/ttyACM0,raw,echo=0 TCP4:192.168.95.247:1235,end-close
//Asterix
socat TCP-LISTEN:1235,reuseaddr,fork,end-close TCP-LISTEN:1236,end-close,reuseaddr,fork

ssh pi@192.168.95.209



