## BaltSMS - SMS Unlock Shop

#### Requirements

* PHP PDO
* allow_url_fopen enabled

#### PHP Class Usage

```
/*
    onstruct class on $baltsms
*/
$baltsms = new baltsms();

/*
    Set a price code from the available price list that can be found in your baltsms.eu control panel. Usually from POST
*/
$baltsms->setPrice(120);

/*
    Set the unlock code received by the user. Usually from POST
*/
$baltsms->setCode();

/*
    Send request to the baltsms.eu server
*/
$baltsms->sendRequest();

/*
    Pull response. If successful, this will return true, otherwise it will return a message working with the alert class.
*/
$response = $baltsms->getResponse();
```

#### Information
This is not for