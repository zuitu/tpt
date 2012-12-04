关于ZTemplate
=============
ZTemplate是超轻量级的PHP模版引擎，全部实现仅仅60行代码，简单实用。


配置ZTemplate
-------------
ZTemplate只包括两个配置参数，DIR_COMPILED和DIR_TEMPLATE，分别表示模版编译目录和模版文件目录。

自定义配置如此的简单：
```php
define('DIR_COMPILED','/compiled_diy');
define('DIR_TEMPLATE','/template_diy');
```
如果不想做任何配置，ZTemplate使用compiled和template作为其默认的参数值。


使用ZTemplate
-------------
php文件示例:
```php
$title = 'welcome';
$users = array('alen', 'blen', 'calon');
include template('main');
```
header.html模版文件示例:
```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="bb5c110b0512ff89999a055118a84509">
<head>
    <meta http-equiv=content-type content="text/html; charset=UTF-8">
    <title>{$title}</title>
    <link rel="shortcut icon" href="static/icon/favicon.ico" />
</head>
<body>
```
main.html模版文件示例:
```html
<!--{include header}-->
<!--{loop $users $index $one}-->
<li>${$index+1}-{$one}</li>
<!--{/loop}-->
<!--{include footer}-->
```
{}作为模版引擎的开始和结束标记，如果{}内部使用php运算符、函数调用等复杂语句，则需在{前加$，正确输出:
```html
1-alen
2-blen
3-calon
```
footer.html模版文件示例：
```html
</body>
</html>
```
否则，标记里面的内容不会被解析，输出如下：
```html
{$index+1}-alen
{$index+1}-blen
{$index+1}-calon
```
模版中可以使用简单的php控制结构，下面的示例中包含了循环结构的使用，另外，还支持if，if/else，if/elseif/else等条件判断，本模版本着“大道至简”的设计思想，简单实用就已经足够了，所以，也仅仅支持这两种控制结构。
```html
<!--{loop $users $index $one}-->
<!--{if $index==0}-->
<li>${md5($one)}-{$one}</li>
<!--{elseif $index==1}-->
<li>${md5($one)}-{$one}</li>
<!--{else}-->
<li>${md5($one)}-{$one}</li>
<!--{/if}-->
<!--{/loop}-->
```
输出：
f3f7eb1421dcfed9a2614712ec608f1b-alen
37670a9c2cc598bcc271612af0617c3c-blen
82a8ea39dd2700b9c6dd207d512bb62a-calon