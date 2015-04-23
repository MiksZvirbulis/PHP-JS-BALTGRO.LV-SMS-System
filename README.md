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
This repository is made to be used only for baltsms.eu registered clients who have the required information to fill in the configuration. This is a shop which works with the plugins acquired in the plugins folder. The documentation for the SMS call above is meant to be used when creating a plugin for a unlock code charge use and not for external inclusion as it requires some definitions in the configuration file.
