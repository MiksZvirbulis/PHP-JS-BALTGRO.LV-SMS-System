## BaltSMS - SMS Unlock Shop

#### Requirements

* PHP PDO
* allow_url_fopen enabled.

#### PHP Class Usage

```
/*
    Construct class on a variable.
*/
$baltsms = new baltsms();

/*
    Set a price code from the available price list that can be found in your baltsms.eu control panel. Usually from POST.
*/
$baltsms->setPrice(120);

/*
    Set the unlock code received by the user. Usually from POST.
*/
$baltsms->setCode();

/*
    Send request to the baltsms.eu server.
*/
$baltsms->sendRequest();

/*
    Pull response. If successful, this will return true, otherwise it will return a message working with the alert function.
*/
$response = $baltsms->getResponse();
```

#### Demo
http://baltgro.mikscode.com/

#### Information
This repository is made to be used only for baltsms.eu registered clients who have the required information to fill in the configuration. This is a shop which works with the plugins acquired in the plugins folder.
The request documentation above is ONLY to be used inside the existing shop by creating a new plugin. This class WILL NOT work by including the class externally as it requires data that is set inside the configuration file.
