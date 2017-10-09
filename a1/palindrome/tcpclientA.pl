#!/usr/bin/perl
#tcpclient.pl

use IO::Socket;

$socket = new IO::Socket::INET (
                                  PeerAddr  => '127.0.0.1',
                                  PeerPort  =>  7778,
                                  Proto => 'tcp',
                               )                
or die "Couldn't connect to Server\n";

        $input = 'perl inputA.pl';
        $socket->recv($recv_data,1024);
	print "RECIEVED: $recv_data"; 
        $socket->send($input);

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
    
