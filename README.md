# TranslationAPI
A translation library for your code from PocketMine-MP 4.0.0 or earlier
## How to use it?
First you initialize the class, then use its methods
```php
//$this = PluginBase class
$translation = new Translation($this);
```
First choose the default language to use if that language does not exist
```php
//use class
use exodus\translation\Language;

//i will use the language of the USA
$translation->setDefaultLanguage(new Language("en_US", "English Language", ["welcome-message" => "%username% joined!!"]));
```
after this...
```php


$translation->send($player, "welcome-message", ["username" => $player->getName()]);
```
this beautiful code xd
