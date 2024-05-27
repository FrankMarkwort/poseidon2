#!/bin/bash
# shellcheck disable=SC2002
cat /srv/poseidon/poseidon2/tests/TestData/yachtDeviceRawWithNavi.log | nc 172.17.0.2 1235