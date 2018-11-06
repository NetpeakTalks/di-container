#!/bin/bash

cd ././

IFS=' ' read -r -a boxs <<< $(docker ps |grep php_di_container)
docker exec -i -t "${boxs[0]}" /bin/bash
