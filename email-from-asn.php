<?php
# SET THE ASN (YOU CAN REPLACE THIS WITH GET OR POST DATA AS NEEDED - AN EXAMPLE ASN FOR EACH RIR IS LISTED HERE. COMMENT OUT OTHER AND UNCOMMENT FOR THE RIR YOU'D LIKE TO TEST)
$asn="210464"; //RIPE
#$asn="6939"; //ARIN
#$asn="4788"; //APNIC
#$asn="262222"; //LACNIC
#$asn="36947"; //AFRINIC

# GET RIR FROM IANA
$cmd="whois -h whois.iana.org AS".$asn;
exec($cmd, $iana); $iana=implode("\n", $iana);
if(preg_match('/^refer:\s*(\S+)/m', $iana, $matches)) { $rir=$matches[1]; }else{ $rir="unknown"; }
unset($cmd); unset($matches); unset($iana);

# MAKE RIR WHOIS COMMAND
if($rir=="unknown"){ exit("NO_RIR_MATCH"); }
if($rir=="whois.ripe.net"){ $cmd="whois -B -h whois.ripe.net AS".$asn; }
if($rir=="whois.arin.net"){ $cmd="whois -h whois.arin.net AS".$asn; }
if($rir=="whois.apnic.net"){ $cmd="whois -h whois.apnic.net AS".$asn; }
if($rir=="whois.lacnic.net"){ $cmd="whois -h whois.lacnic.net AS".$asn; }
if($rir=="whois.afrinic.net"){ $cmd="whois -B -h whois.afrinic.net AS".$asn; }

# GET WHOIS OUTPUT
exec($cmd, $output); $output=implode("\n", $output);
unset($cmd);

# GET THE EMAILS
$emails = array();
$pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
preg_match_all($pattern, $output, $matches);
$emails = array_unique($matches[0]);
unset($pattern); unset($matches); unset($output);

# FILTER OUT RIR EMAILS
$emails = array_filter($emails, fn($record) => stripos($record, "ripe.net") === false);
$emails = array_filter($emails, fn($record) => stripos($record, "arin.net") === false);
$emails = array_filter($emails, fn($record) => stripos($record, "apnic.net") === false);
$emails = array_filter($emails, fn($record) => stripos($record, "lacnic.net") === false);
$emails = array_filter($emails, fn($record) => stripos($record, "afrinic.net") === false);
$emails = array_values($emails);

# PRINT EMAILS (CAN BE USED IN YOUR SCRIPT INSTEAD AND YOU DON'T HAVE TO PRINT THEM, THIS IS FOR ILLUSTRATION ONLY)
print_r($emails);

exit();
?>
