Place the original and fixed version of index.php

Highlight your changes and where you spot vulnerabilities. It will make it easier for you to get part marks.



Passwords need to be hashed and salted in order to be secure. The password database uses unsalted hashes to store everyone’s passwords. A file upload flaw allows an attacker to retrieve the password file. All of the unsalted hashes can be exposed with a rainbow table of precalculated hashes.  

A site simply doesn’t use SSL for all authenticated pages. Attacker simply monitors network traffic (like an open wireless network), and steals the user’s session cookie. Attacker then replays this cookie and hijacks the user’s session, accessing the user’s private data. 

SSL needs to be activated in order to secure communications.

This can be achieved by adusting the httpd.conf file when using apache and installing certificates.
Following the link below will walk you through setting up ssl in order to secure communications. 

Link: https://www.sslshopper.com/apache-server-ssl-installation-instructions.html
