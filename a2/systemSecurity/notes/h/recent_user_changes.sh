#!/bin/bash

cat /mnt/clean/etc/shadow | awk -F ":" '{printf $3 $1 " \n"}' | sort -nr > recent_user_clean.txt
cat /mnt/corr/etc/shadow | awk -F ":" '{printf $3 $1 " \n"}' | sort -nr > recent_user_corr.txt


diff recent_user_clean.txt recent_user_corr.txt > recent_user_diff.txt
