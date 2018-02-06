#!/bin/bash


export LD_LIBRARY_PATH=/root/ProgMonitoringActivite/lib/

if ! pgrep -x "EnregTV" > /dev/null
then
# Usage : ./BruitLabo  tps_ms_tv_on_off
    ./EnregTV/EnregTV 60000 &
fi

if ! pgrep -x "DetectZone" > /dev/null
then
    ./DetectZone/DetectZone &
fi

if ! pgrep -x "WSListener" > /dev/null
then
# Usage : ./WSListener Address-IP-machine-BruitLabo  Address-IP-machine-EnregTV Address-IP-machine-DetectZone
    ./WSListener/WSListener 192.168.197.52 127.0.0.1 127.0.0.1  &
fi
