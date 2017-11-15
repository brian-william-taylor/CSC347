#!/bin/bash

function find_hash(){
	if (($# == 1))
		then openssl sha256 "$1" | awk '{print $2}'; fi
}

function check_hash(){
	if (($# == 2))
		then h=$(find_hash "$1"); 
		if [ "$h" == "$2" ]
			then echo "$1";
		fi
	fi
}

function run(){
	hash=$(cat threeLaws.txt.sha256 | awk '{printf $0}');
	for ((i=1;i<=3;i+=1)) do
		echo "threeLaws$i.txt";
		#check_hash "threeLaws$i.txt" "$hash";
	done
}
#run;
check_hash "threeLaws2.txt" "aaa34b5687295846a1eb94b00676100f9294bc43534f8fbcb033a4342f24e766";
