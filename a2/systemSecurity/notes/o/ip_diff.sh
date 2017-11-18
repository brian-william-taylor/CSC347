#!/bin/bash

last -i -f /mnt/corr/var/log/wtmp > corr_remote_logins.txt
last -i -f /mnt/clean/var/log/wtmp > clean_remote_logins.txt

diff corr_remote_logins.txt clean_remote_logins.txt > login_remote_diff.txt

