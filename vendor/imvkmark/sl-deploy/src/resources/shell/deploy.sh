#!/bin/bash
aim_path=$1
cd ${aim_path}
echo $PWD
/usr/bin/git pull origin master >/dev/null 2>&1
if [ $? -eq 0 ];then
echo "OK"
else
   /usr/bin/git fetch -f
   /usr/bin/git reset --hard
   /usr/bin/git pull origin master
fi
