#!/bin/bash

last -f /mnt/clean/var/log/wtmp > ../recent_login_clean.txt
last -f /mnt/corr/var/log/wtmp > ../recent_login_corr.txt

