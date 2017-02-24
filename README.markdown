PHP SDK for EDB-Brugs
--

[![Build Status](https://travis-ci.org/vih/edbbrugs-php-sdk.png?branch=master)](https://travis-ci.org/vih/edbbrugs-php-sdk)

PHP 5 SDK to communicate with [De frie skolers EDB-BRUGS](http://edb-brugs.dk).

The service communicates via SOAP to EDB-BRUGS. You are only able to create new registrations which has to manually manipulated and put into the correct courses using the EDB-BRUGS Windows program.

To get access to the service, you need to contact the [support at EDB-BRUGS](http://edb-brugs.dk). You need following information:

- WSDL for the SOAP-service (append ?wsdl to the WSDL-endpoint you recieve from EDB-BRUGS)
- Username
- Password
- School Code

Testing
--

During testing you need to manually delete the web registrations using the Windows program.
