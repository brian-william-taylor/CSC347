#!/bin/bash
find /mnt/clean | xargs ls -ltd > sorted_changes_clean.txt
find /mnt/corr | xargs ls -ltd > sorted_changes_corr.txt

diff sorted_changes_clean.txt sorted_changes_corr.txct > sorted_changes_diff.txt
