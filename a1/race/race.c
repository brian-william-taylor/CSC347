#include <stdio.h>
#include <unistd.h>

/**

1) as root, compile race.c (use the makefile), make race
    cd /questions/race
    make 

2) Verify that hacker can not view or edit /etc/passwd2

3) Explain what the setuid bit being set means for race, when run by hacker.

4) As hacker, run /questions/race/race to understand how it works and what it does

5) As hacker, explain how hacker exploits /questions/race/race
   so that they modify the root only readable and writable file /etc/passwd2

6) As hacker, explain how hacker exploits /questions/race/race
   so that they end up with a root shell

   Be careful when doing this you may lock yourself out of the system!!
   I have made a backup of /etc/passwd in /etc/passwd.bak
   make sure you have a root shell when playing with the passwd
   file.

7) Fix the code below so that it eliminates the race condition and 
   allows race to safely write to /tmp/permitted, no matter which user
   owns the file.

References: 
http://www.cis.syr.edu/~wedu/seed/Labs_12.04/Software/Race_Condition/Race_Condition.pdf
http://www.csl.mtu.edu/cs3451/www/notes/ch6%20-%20Adding%20new%20users.pdf

**/

int main(int argc, char ** argv) {
	char * fn = "/tmp/permitted";
	char buffer[128];
	FILE *fp;

    
    uid_t real_uid = getuid();
    uid_t effective_uid = geteuid();

    seteuid(real_uid);

    //Sets the uid as the user instead of root to block access to any restricted files

    fp = fopen(fn, "w");
	if(fp != -1){
		scanf("%100s", buffer );
		fwrite("\n", sizeof(char), 1, fp);
		fwrite(buffer, sizeof(char), strlen(buffer), fp);
		fclose(fp);
	} else {
		printf("No permission \n");
	}

    seteuid(effective_uid);
}
