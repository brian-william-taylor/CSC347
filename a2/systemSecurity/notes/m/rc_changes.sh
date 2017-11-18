#!/bin/bash


diff -rq /mnt/corr/etc/rc.d /mnt/clean/etc/rc.d > rc_d_diff.txt
diff -rq /mnt/corr/etc/rc0.d /mnt/clean/etc/rc0.d > rc0_d_diff.txt
diff -rq /mnt/corr/etc/rc1.d /mnt/clean/etc/rc1.d > rc1_d_diff.txt
diff -rq /mnt/corr/etc/rc2.d /mnt/clean/etc/rc2.d > rc2_d_diff.txt
diff -rq /mnt/corr/etc/rc3.d /mnt/clean/etc/rc3.d > rc3_d_diff.txt
diff -rq /mnt/corr/etc/rc4.d /mnt/clean/etc/rc4.d > rc4_d_diff.txt
diff -rq /mnt/corr/etc/rc5.d /mnt/clean/etc/rc5.d > rc5_d_diff.txt
diff -rq /mnt/corr/etc/rc6.d /mnt/clean/etc/rc6.d > rc6_d_diff.txt

