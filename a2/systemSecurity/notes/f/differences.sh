#!/bin/bash


diff --brief -r /etc/mnt/corr /etc/mnt/clean > brief_diff.txt
diff -r /etc/mnt/corr /etc/mnt/clean  > full_diff.txt

