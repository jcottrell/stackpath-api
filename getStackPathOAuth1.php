<?php

if (empty($argv) || empty($argv[1]) || empty($argv[2]) || empty($argv[3])) {
    echo "Please supply the comsumer key, consumer secret, and the company alias when invoking this script.\n";
    echo "This would appear something like:\n\n";
    echo " > php getStackPathOAuth1.php somelongletternumbermixofcharacters anotherlongletternumbermix athirdshortermix\n\n";
    die;
}

$consumerKey = $argv[1];
$consumerSecret = $argv[2];
$companyAlias = $argv[3];

function generateNonce(int $length, $cs = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') : string
{
    for ($result = ''; strlen($result) <= $length;) {
        $result .= $cs[rand(0, strlen($cs) - 1)];
    }

    return $result;
}

function getSignatureBasePairs($pairs)
{
    ksort($pairs);
    return urlencode(http_build_query($pairs));
}

$url = 'https://api.stackpath.com/v1/'.$companyAlias.'/logs';

$nonce = generateNonce(32);
$timestamp = time();

$parameterPairs = [
    'limit'                 => 2,
    'status'                => '200',
];
$oauthPairs = [
    'oauth_consumer_key'    => $consumerKey,
    'oauth_nonce'           => $nonce,
    'oauth_signature_method'=> 'HMAC-SHA1',
    'oauth_timestamp'       => $timestamp,
    'oauth_version'         => '1.0'
];

$signatureBase = 'GET'.'&'.
    urlencode(strtolower($url)).'&'.
    getSignatureBasePairs(array_merge($parameterPairs, $oauthPairs));
$signature = urlencode(base64_encode(hash_hmac('sha1', $signatureBase, urlencode($consumerSecret).'&', true)));
$oauthPairs['oauth_signature'] = $signature;
ksort($oauthPairs);

$authHeader = 'Authorization: OAuth ';
$authHeader.= implode('', array_map(function ($key, $value) {
    return $key.'="'.$value.'"';
}, array_keys($oauthPairs), $oauthPairs));
$header = [$authHeader];

$ch = curl_init($url.'?'.http_build_query($parameterPairs));
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

$result = curl_exec($ch);
if (curl_error($ch)) {
    echo 'Error: '.curl_error($ch)."\n";
} else {
    var_dump(json_decode($result));
}
curl_close($ch);
