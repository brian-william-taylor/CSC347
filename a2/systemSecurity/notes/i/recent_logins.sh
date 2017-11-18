#!/bin/bash

last -f /mnt/clean/var/log/wtmp > recent_login_clean.txt
last -f /mnt/corr/var/log/wtmp > recent_login_corr.txt

diff recent_login_clean.txt recent_login_corr.txt > recent_login_diff.txt
