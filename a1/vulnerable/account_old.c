#include<stdio.h>
#include<stdlib.h>
#include <sys/types.h>
#include <unistd.h>
$include <string.h>
/**

You are doing a code review on the following credit trading system...

As root, on the RH7.2 A1 Virtual Machine

	cp vulnerable.tar.gz /
	tar -zxf vulnerable.tar.gz
	cd vulnerable

	make account
	chmod +s account

NOTE: Make sure the permssions are correct after you recompile account!

Make sure permissions are as follows: ls -al 

drwxr-xr-x    3 root     root         4096 Nov  4 17:20 .
drwxr-xr-x   20 root     root         4096 Nov  4 17:08 ..
-rwsr-sr-x    1 root     root        17350 Nov  4 17:15 account
-rw-------    1 root     root         4711 Nov  4 17:20 account.c
drwxr-xr-x    2 root     root         4096 Nov  4 17:17 accounts
-rw-------    1 root     root            0 Nov  4 17:17 log
-rw-------    1 root     root            0 Nov  4 17:17 passwords

Each user can now execute

/vulnerable/account myPassword   # to create my account with 100 credits, initialized with myPassword
/vulnerable/account myPassowrd 20 otherUser # give 20 credits to otherUser

1) Identify any bufferoverrun, integer overflow, canonical naming, priviledge escalation, denial of service etc. 
   issues in this code. Submit a copy of the code annotated with the issues. To make it easier to 
   find your annotations write ISSUE: before each issue you identify.
2) Demonstrate that the above vulnerabilities can be exploited and list the potential outcomes.
3) Fix the code so that the vulnerabilities are eliminated, or describe how the vulnerability/exploit
   should be addressed.

*/

// The user is not in the system, so add them and their password
int addUser(char * user, char * password){
	FILE * file;
	char fileName[100];
	// ISSUE: If "/vulnerable/passwords" is a symboluc link, then the new username and password are compromised
	file=fopen("/vulnerable/passwords","r+");
	// ^ If /vulnerable/passwords is a symbolic link then the username and password are compromised
	fseek(file, 0, SEEK_END);
	// ISSUE: Canonical naming. What if the user contains a space?
	fputs(user,file);
	fputs(" ",file);
	fputs(password,file);
	fputs("\n",file);
	fclose(file);

	setAccount(user,100);

	return 0;
}
int getAccount(char * user){
	FILE * file;
	char fileName[100];
	int amount=0;

	strncpy(fileName, "/vulnerable/accounts/",100);
	// Issue: "/vulnerable/accounts/" concatenated with user may exceed the 100 limit.
	// Issue: user could be a link to any file (by traversing the the parent directory .. then anywhere desired)
	strncat(fileName, user, 100);
	// ^ Use remainder = std::begin(fileName) - std::end(fileName) for strncat limit
	// What if file doesn't exist?
	if(file=fopen(fileName, "r")){
		fscanf(file, "%d", &amount);
		// ^ Use fscanf limit to keep the amount within int limits, maybe
		fclose(file);
	} else {
		return -1; // to signify that an account does not exist
	}
	return amount;
}
int setAccount(char * user, int amount){
	FILE * file;
	char fileName[100], amountStr[100];
	strncpy(fileName, "/vulnerable/accounts/",100);
	// Issue: "/vulnerable/accounts/" concatenated with user may exceed the 100 limit.
	// Issue: user could be a link to any file (by traversing the the parent directory ... then anywhere desired)
	strncat(fileName, user, 100);
	// ^Use remainder = std::begin(fileName) - std::end(fileName) for strncat limit
	file=fopen(fileName, "w");
	// ^ If /vulnerable/passwords is a symbolic link then the set amount can be applied to anyone
	sprintf(amountStr, "%d", amount);
	// ^ No limit on printing, could create buffer overrun
	fputs(amountStr,file);
	fclose(file);

}
int logTransaction(char * transaction){
	FILE * file;
	file=fopen("/vulnerable/log","r+");
	// ^ If /vulnerable/passwords is a symbolic link then the transaction could be deposited into another acount
	fseek(file, 0, SEEK_END);
	fputs(transaction,file);
	fputs("\n",file);
	fclose(file);
	return 0;
}

int authenticate(char *user, char *password){
 	FILE * file;
	char u[100], p[100];

	file=fopen("/vulnerable/passwords","r+");
	// ^ If /vulnerable/passwords is a symbolic link then the username and password are compromised
	while(!feof(file)){
		fscanf(file,"%s %s\n",u, p);
		// ^ Use fscanf/fprintf limit ex %2s
		if(strncmp(user,u,100)==0){
			if(strncmp(password, p, 100)==0)return 1;
			else return 0;
		}
	}
	fclose(file);
	return 2;
}
int report(char * user){
	char buffer[2048];
	strncpy(buffer, "cat /vulnerable/accounts/", 2048);
	strncat(buffer,user,2048);
	// ^ Use remainder = std::begin(buffer) - std::end(buffer) for strncat limit
        setuid(0);
	// ISSUE: buffer could be "cat /vulnerable/accounts/some_user;/some/malicious/command ex /bin/sh
	system(buffer);
	// ^ Unsafe, buffer could be "cat /vulnerable/accounts/some_user;/some/malicious/command ex /bin/sh
	printf("\n");
}

int main(int argc, char *argv[]){
	char user[100];
	char password[100];
	char transaction[2048];
	int auth;

	int i;

	if(argc!=2 && argc!=4){
		printf("account password (to setup/report on your account)\n");
		printf("account password amount targetAccount (to transfer)\n");
		return 0;
	}

	strncpy(user,getenv("USER"),100); // determine who is running this
	// ^ maybe there could be malicious code in $USER

	/* for auditing purposes */
	transaction[0]='\0';
	strncat(transaction,user,2048);
	strncat(transaction,": ",2048);
	// ^^ Use remainder = std::begin(transaction) - std::end(transaction) for strncat limit
	
	for(i=1;i<argc;i++){
		strncat(transaction,argv[i],2048);
		strncat(transaction," ",2048);
		// ^^ Use remainder = std::begin(transaction) - std::end(transaction) for strncat limit
	}

	strncpy(password,argv[1],99);
	// ^ Use remainder = std::begin(transaction) - std::end(transaction) for strncat limit
	password[99]='\0';
	auth=authenticate(user, password);

	if(argc==2){ 
	
		if(auth==2){
			addUser(user, password);
			printf("Your account has:\n");
			report(user);
		} else if(auth==1){
			printf("Your account has:\n");
			report(user);
		} else {
			printf("You have not been authenticated\n");
		}
	} else if(argc==4){ // perform a transfer to another account
		if(auth==1){
			int fromAmount, amount, toAmount;
			amount=atoi(argv[2]);
			// ^ potential int overflow. Check min and max before converting
			fromAmount=getAccount(user);
			toAmount=getAccount(argv[3]);
			if(toAmount==-1){
				printf("account %s does not exist\n",argv[3]);
			} else if(fromAmount-amount>0){
				printf("Your account had:\n");
				report(user);

				fromAmount=fromAmount-amount;
				toAmount=toAmount+amount;
				setAccount(user,fromAmount);
				setAccount(argv[3],toAmount);

				printf("Your account now has:\n");
				report(user);
			} else {
				printf("You do not have sufficient credits.\n");
			}
		} else { 
			printf("You have not been authenticated\n");
		} 
	}

	/* in any case, log the attempt */
	logTransaction(transaction);

	return 0;
}

