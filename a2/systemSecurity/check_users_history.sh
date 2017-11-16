#!/bin/bash

while read p;
do
    cat "${p}/.bash_history"
done <users.txt 
