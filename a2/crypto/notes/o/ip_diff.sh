#!/bin/bash

last -i -f /mnt/corr/var/log/wtmp > ../corr_logins.txt
last -i -f /mnt/clean/var/log/wtmp > ../clean_logins.txt

diff ../corr_logins.txt ../clean_logins.txt > ../login_diff.txt

