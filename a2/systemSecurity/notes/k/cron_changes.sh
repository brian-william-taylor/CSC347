#!/bin/bash


diff -rq /mnt/corr/etc/cron.weekly /mnt/clean/etc/cron.weekly > cron_diff_weekly.txt
diff -rq /mnt/corr/etc/cron.monthly /mnt/clean/etc/cron.monthly > cron_diff_monthly.txt
diff -rq /mnt/corr/etc/cron.hourly /mnt/clean/etc/cron.hourly > cron_diff_hourly.txt
diff -rq /mnt/corr/etc/cron.daily /mnt/clean/etc/cron.daily > cron_diff_daily.txt
diff -rq /mnt/corr/etc/cron.d /mnt/clean/etc/cron.d> cron_diff_d.txt

