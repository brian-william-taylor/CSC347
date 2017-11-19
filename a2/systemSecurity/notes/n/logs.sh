#!/bin/bash

diff -rq /mnt/corr/var/log /mnt/clean/var/log > log_diff.txt
diff -r /mnt/corr/var/log /mnt/clean/var/log > log_diff_full.txt

