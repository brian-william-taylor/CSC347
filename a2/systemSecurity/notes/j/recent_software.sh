#!/bin/bash

# diff  /mnt/corr/var/log/rpmpkgs /mnt/clean/var/log/rpmpkgs > software_changes.txt

rpm -qav --dbpath /mnt/clean/var/lib/rpm --last > clean_software_time.txt
rpm -qav --dbpath /mnt/corr/var/lib/rpm --last > corr_software_time.txt

diff clean_software_time.txt corr_software_time.txt > software_time_diff.txt

sort /mnt/corr/var/log/rpmpkgs > corrp
sort /mnt/clean/var/log/rpmpkgs > clrp
diff corrp clrp > diffrp
