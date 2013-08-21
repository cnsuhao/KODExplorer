#!/usr/bin/perl

use Something qw(func1 func2);

# strings
my $s1 = qq'single line';
our $s2 = q(multi-
              line);

=item Something
	Example.
=cut

my $html=<<'HTML'
<html>
<title>hi!</title>
</html>
HTML

print "first,".join(',', 'second', qq~third~);

if($s1 =~ m[(?<!\s)(l.ne)\z]o) {
	$h->{$1}=$$.' predefined variables';
	$s2 =~ s/\-line//ox;
	$s1 =~ s[
		  line ]
		[
		  block
		]ox;
}

1; # numbers and comments

__END__
something...

