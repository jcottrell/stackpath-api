#Stack Path API usage (api.stackpath.com)
This is my attempt to use StackPath's API with PHP. I was unable to find an example when I looked so after some research, here is my attempt.

My interest was finding the download results (http status 200) for downloaded files. This is available through the Raw Logs in StackPath's API but it took some work to get there. The current version of this repository just `var_dump`s the latest two log entries that registered as 200 / success statuses (not 206, partial download or any other status).

## Use
1. [Log in](https://app.stackpath.com/account/api)
1. [Create a new application](https://app.stackpath.com/account/api/create)
1. At the command line use your system's php to call the script (`getStackPathOAuth1.php`) and add the consumer key, consumer secret, and company alias as arguments.
1. Hit enter / run
E.g.:  
   > php getStackPathOAuth1.php somelongletternumbermixofcharacters anotherlongletternumbermix athirdshortermix

## Helpful pages
* [StackPath API](https://api.stackpath.com/) (especially under Authentication and Raw Logs)
* [OAuthBible.com 1.0a Two-Legged](http://oauthbible.com/#oauth-10a-two-legged)
* [OAuth.net](https://oauth.net/core/1.0a/) (especially 5.1 and 9)

## Other notes
* The biggest part is getting the OAuth Authorization header correct.
* The alphabetical ordering of the signature base was unexpected.
* The urlencoding of the url (without attached GET variables) was unexpected.
* The url to be `curl`ed has to include the GET variables, again unexpected.
* The excessive advice about request tokens and authorization distracted me from obtaining the Raw Logs via their own URL and the straight Authorization header, again unexpected:
`'Authorization: OAuth oauth_consumer_key="longstringfromStackPathApp"oauth_nonce="random32characterString"etcetc'`
* Unsupported RSA-SHA1 was unexpected and undocumented, used HMAC-SHA1 instead.
* Generally OAuth has many options and StackPath seems to know its own implementation well enough to think it's straight forward. I'm unexperience in OAuth and did not find it so.
* OAuth 1.0a Two-Legged is not documented well in the sense of what is shares and what is different from other implementations.
