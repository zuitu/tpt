关于ZTemplate
=============
ZTemplate是超轻量级的PHP模版引擎，全部实现仅仅60行代码，简单实用。

ZTemplate配置
-------------
ZTemplate只包括两个配置参数，DIR_COMPILED和DIR_TEMPLATE，分别表示模版编译目录和模版文件目录。

自定义配置如此的简单：
```php
define('DIR_COMPILED','/compiled_diy');
define('DIR_TEMPLATE','/template_diy');
```
如果不想做任何配置，ZTemplate使用compiled和template作为其默认的参数值。