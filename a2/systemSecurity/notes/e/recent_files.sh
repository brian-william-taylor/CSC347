#!/bin/bash
find /mnt/clean -print0 | xargs -0 ls -ltd > sorted_changes_clean.txt
find /mnt/corr -print0 | xargs -0 ls -ltd > sorted_changes_corr.txt

diff sorted_changes_clean.txt sorted_changes_corr.txt > sorted_changes_diff.txt
