#!/bin/bash

john --show /mnt/corr/etc/shadow > hacked_corr.txt
john --show /mnt/clean/etc/shadow > hacked_clean.txt

diff hacked_corr.txt hacked_clean.txt > hacked_diff.txt
