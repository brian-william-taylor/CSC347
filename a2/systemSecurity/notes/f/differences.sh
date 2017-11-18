#!/bin/bash


diff --brief -r /mnt/corr /mnt/clean > brief_diff.txt
diff -r /mnt/corr /mnt/clean  > full_diff.txt

