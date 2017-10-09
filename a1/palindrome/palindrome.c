#include<stdio.h>
#include<string.h>

char *secret = "you got me!!";

// return 1 if we should continue, 0 if we should stop
int processOneLine(){
	char s[1024];
	char *s1,*s2;
	int isPalindrome=1; // it is a palindrome until we find out otherwise
	s1=s;
	// Read only 1024 characters from the input
	int charCount = 0;
	while(charCount < 1024){
		*s1=getchar();
		if(*s1=='\n')*s1='\0';
		if(*s1=='\0')break;
		s1++;
		charCount++;
	}

	//Add a null terminator to the string
	s[1023] = '\0';

	s2=s;
	s1--;
	while(s2<s1){
		if(*s1!=*s2){
			isPalindrome=0;
			break;
		}
		s1--;
		s2++;
	}

	//Print the user input as a string
	printf('%s', s);
	if(isPalindrome){
		printf(" is a palindrome\n");
	} else {
		printf(" is not a palindrome\n");
	}
	fflush(stdout);
	if(strncmp(s,"quit",4)==0)return 0;
	return 1;
}

int main(int argc, char **argv){
	printf("Palindrome server, 'quit' to quit:\n");
	fflush(stdout);
	while(1){
		if(!processOneLine())break;
	}	
	return(0);
}
