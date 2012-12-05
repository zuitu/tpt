<?php
/**
 * @author: zuitu@foxmail.com
 */
if(false==defined('DIR_COMPILED')) define('DIR_COMPILED','/compiled');
if(false==defined('DIR_TEMPLATE')) define('DIR_TEMPLATE','/template');

function template($tFile) {

    $tFileN = preg_replace( '/\.html$/', '', $tFile);
    $tFile = DIR_TEMPLATE . '/' . $tFileN . '.html';
    $cFile = DIR_COMPILED . '/' . str_replace('/','_',$tFileN) . '.php';

    if(false===file_exists($tFile)){
        die("Templace File [$tFile] Not Found!");
    }

    if(false===file_exists($cFile) 
            || @filemtime($tFile) > @filemtime($cFile)) {
        __parse($tFile,$cFile);
    }

    return $cFile;
}

function __parsecall($matches) {
    return '<?php include template("'.$matches[1].'"); ?>';
}

function __parse($tFile,$cFile) {

    $fileContent = false;

    if(!($fileContent = file_get_contents($tFile)))
        return false;

    $fileContent = preg_replace( '/^(\xef\xbb\xbf)/', '', $fileContent ); //EFBBBF   
    $fileContent = preg_replace("/\<\!\-\-\s*\\\$\{(.+?)\}\s*\-\-\>/ies", "__replace('<?php \\1; ?>')", $fileContent);
    $fileContent = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\\\ \-\'\,\%\*\/\.\(\)\>\'\"\$\x7f-\xff]+)\}/s", "<?php echo \\1; ?>", $fileContent);
    $fileContent = preg_replace("/\\\$\{(.+?)\}/ies", "__replace('<?php echo \\1; ?>')", $fileContent);
    $fileContent = preg_replace("/\<\!\-\-\s*\{else\s*if\s+(.+?)\}\s*\-\-\>/ies", "__replace('<?php } else if(\\1) { ?>')", $fileContent);
    $fileContent = preg_replace("/\<\!\-\-\s*\{elif\s+(.+?)\}\s*\-\-\>/ies", "__replace('<?php } else if(\\1) { ?>')", $fileContent);
    $fileContent = preg_replace("/\<\!\-\-\s*\{else\}\s*\-\-\>/is", "<?php } else { ?>", $fileContent);

    for($i = 0; $i < 5; ++$i) {
        $fileContent = preg_replace("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/ies", "__replace('<?php if(is_array(\\1)){foreach(\\1 AS \\2=>\\3) { ?>\\4<?php }}?>')", $fileContent);
        $fileContent = preg_replace("/\<\!\-\-\s*\{loop\s+(\S+)\s+(\S+)\s*\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/loop\}\s*\-\-\>/ies", "__replace('<?php if(is_array(\\1)){foreach(\\1 AS \\2) { ?>\\3<?php }}?>')", $fileContent);
        $fileContent = preg_replace("/\<\!\-\-\s*\{if\s+(.+?)\}\s*\-\-\>(.+?)\<\!\-\-\s*\{\/if\}\s*\-\-\>/ies", "__replace('<?php if(\\1){?>\\2<?php }?>')", $fileContent);
    }

    //Add for call <!--{include othertpl}-->
    $fileContent = preg_replace("#<!--\s*{\s*include\s+([^\{\}]+)\s*\}\s*-->#i", '<?php include template("\\1");?>', $fileContent);

    //Add value namespace
    if(!file_put_contents($cFile,$fileContent))	
        return false;

    return true;
}

function __replace($string) {
    return str_replace('\"', '"', $string);
}
?>