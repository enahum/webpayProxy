WebpayProxy - PHP Proxy for Webpay Webservice normal transaction
========================================

webpayProxy is a PHP project written with [CodeIgniter](http:://codeigniter.com) that lets you forget about the WSSE services from Webpay and lets you focus on your code. 

Make a few configurations, copy your certificates and deploy the proxy.


Configuration
--------------------

1. Create an environment variable called DATABASE_URL with the Connection string for your database.

    ``` console
    export DATABASE_URL=postgres://webpay:webpay@localhost:6212/webpay
    ```
    The proxy uses PostgreSQL to store persistance between request. If you want to use another database manager change the configuration in `/application/config/database.php`

2. Change the values in `/application/config/constants.php` if they differ

    ``` php
    define('COMMERCE_ID', ''); //place your commerce code here
    define('WEBPAY_WSDL', 'https://webpay3g.orangepeople.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl');
    define('PRIVATE_KEY', 'certificates/private.key'); // this is the path to your certificate private key
    define('STORE_CERT', 'certificates/store.crt'); // this is the path of the certificate that was sent to webpay
    define('SERVER_CERT', 'certificates/server.crt'); //this is the path for webpay certificate
    ```

3. Copy your private.key and store.crt to the `/certificates folder`, if the names are different you should change the path in the constants mention in step 2.

Deploy
----------------------------------------

Deploy the proyect to any VPS with PHP5 containing mcrypt and pgsql modules, the last one only if your going to use PostgreSQL.

Deployment to Heroku
-----------------
Follow the instructions [in the documentation](https://devcenter.heroku.com/articles/deploying-php)

Requirements
------------

PHP 5.5.9 or above (at least 5.3.4 recommended to avoid potential bugs)<br />
PostgreSQL

Integration
-------------

1. Make a POST request to <your project url>/transaction/init in application/x-www-form-urlencoded with the following vars

    ```
    finalUrl: The Url to be redirected after the transaction has been completed, either with or without errors
    sessionId: An identifier of the order
    buyOrder: The order number
    amount: The amount to be charged
    ```
    
2. Accept POST request in the finalUrl to handle the response of the transaction.
    
    You need to handle if the transaction has been successful or had errors, if the transaction had errors you'll recieve a error post var with a message of the cause of the error.

3.- Make a GET request to <your project url>/transaction/complete/<token_ws> to get the exact response from webpay