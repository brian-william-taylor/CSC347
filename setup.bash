#!/bin/bash
# Make sure you do this as root, and all files and directories
# are owned by root

cd /vulnerable
chmod 755 .
make account
chmod 755 account
chmod +s account
rm -fr accounts log passwords
mkdir accounts
touch log 
touch passwords
chmod 700 accounts
chmod 600 log passwords
