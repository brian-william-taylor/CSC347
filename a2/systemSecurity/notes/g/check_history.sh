#!/bin/bash



cat /mnt/clean/etc/passwd |  cut -d : -f 6 | sort -nr > user_clean.txt 
cat /mnt/corr/etc/passwd | cut -d : -f 6 | sort -nr > user_corr.txt 

diff user_clean.txt user_corr.txt > user_diff.txt

while read p;
do
	echo "${p}-------------------------------------"    
	cat "/mnt/corr${p}/.bash_history"
    done <user_corr.txt  > history_corr.txt 

while read p;
do
	echo "${p}-------------------------------------"    
	cat "/mnt/clean${p}/.bash_history"
    done <user_clean.txt  > history_clean.txt


diff history_corr.txt history_clean.txt > history_diff.txt
