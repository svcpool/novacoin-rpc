Novacoin RPC actions
====================
Novacoin

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist svcpool/novacoin-rpc "*"
```

or add

```
"svcpool/novacoin-rpc": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \Svcpool\NovacoinRpc\AutoloadExample::widget(); ?>```