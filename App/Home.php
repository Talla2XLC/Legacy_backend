<?php

namespace App;

use Parsedown;

class Home
{
    public function index()
    {
?>

<!-- <link href='http://yastatic.net/highlightjs/8.2/styles/solarized_light.min.css' rel='stylesheet' />-->
<style>
    body {
        background: #858585;
        color: #2c2c2c;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    table {
        border-collapse: collapse;
        background-color: #ffffff;
        color: #363636;

    }

    table tr td,
    th {
        padding: 3px;
        border: 1px solid #c5c5c5;
    }

    markdown-body .highlight {
        margin-bottom: 16px;
    }

    * {
        box-sizing: border-box;
    }

    a {
        color: #4450f5;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }



    .markdown-body {
        font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji;
        font-size: 16px;
        line-height: 1.5;
        word-wrap: break-word;
    }


    code {
        border-radius: 3px 3px 3px 3px;
        color: #333333;
        font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
        font-size: 16px;
        padding: 0 3px 2px;
        background-color: #079cf1;
        font-weight: bolt;
    }


    .body_text {
        display: none;
    }

    .header {
        cursor: pointer;
        border-bottom: 1px solid #454545;
    }

    /*
Atom One Dark by Daniel Gamage
Original One Dark Syntax theme from https://github.com/atom/one-dark-syntax
base:    #282c34
mono-1:  #abb2bf
mono-2:  #818896
mono-3:  #5c6370
hue-1:   #56b6c2
hue-2:   #61aeee
hue-3:   #c678dd
hue-4:   #98c379
hue-5:   #e06c75
hue-5-2: #be5046
hue-6:   #d19a66
hue-6-2: #e6c07b
*/

    .hljs {
        display: block;
        overflow-x: auto;
        padding: 0.5em;
        color: #abb2bf;
        background: #282c34;
    }

    .hljs-comment,
    .hljs-quote {
        color: #5c6370;
        font-style: italic;
    }

    .hljs-doctag,
    .hljs-keyword,
    .hljs-formula {
        color: #c678dd;
    }

    .hljs-section,
    .hljs-name,
    .hljs-selector-tag,
    .hljs-deletion,
    .hljs-subst {
        color: #e06c75;
    }

    .hljs-literal {
        color: #56b6c2;
    }

    .hljs-string,
    .hljs-regexp,
    .hljs-addition,
    .hljs-attribute,
    .hljs-meta-string {
        color: #98c379;
    }

    .hljs-built_in,
    .hljs-class .hljs-title {
        color: #e6c07b;
    }

    .hljs-attr,
    .hljs-variable,
    .hljs-template-variable,
    .hljs-type,
    .hljs-selector-class,
    .hljs-selector-attr,
    .hljs-selector-pseudo,
    .hljs-number {
        color: #d19a66;
    }

    .hljs-symbol,
    .hljs-bullet,
    .hljs-link,
    .hljs-meta,
    .hljs-selector-id,
    .hljs-title {
        color: #61aeee;
    }

    .hljs-emphasis {
        font-style: italic;
    }

    .hljs-strong {
        font-weight: bold;
    }

    .hljs-link {
        text-decoration: underline;
    }

    /*end*/
</style>

<?php
        echo '<h1>Документации API Memory-Lane</h1>';
        echo '<p>Добро пожаловать в документацию API Memory-Lane, для продолжении следуйте по сылкам.</p>';
        $dir = 'Working API';
        $Parsedown = new Parsedown();
        if ($handle = opendir($dir)) {

            while (false !== ($inDir = readdir($handle))) {
                if ($inDir != "." && $inDir != "..") {
                    $dirs = $dir . '/' . $inDir;
                    //echo $dirs . '<br>';
                    if ($indir = opendir($dirs)) {
                        while (false !== ($file = readdir($indir))) {
                            if ($file != "." && $file != "..") {
                                $dirFile = $dirs . '/' . $file;
                                $text = file_get_contents($dirFile);
                                echo '<h2 class="header">' . $inDir . '</h2>';
                                echo '<div class="body_text">';
                                echo $Parsedown->text($text);
                                echo '</div>';
                            }
                        }
                    }
                }
            }
        }
        //echo $dirs;
        /*
        while (false !== ($file = readdir($dirs))) {
            if ($file != "." && $file != "..") {
                $file = file_get_contents($file);

                echo $Parsedown->text($file);
                echo '<br>--------------------------------<br>';
            }
        }
        */
    }
}
?>
<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script async src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
<script type="text/javascript">
    !function ($) {
        $(function () {
            window.prettyPrint && prettyPrint()
        })
    }(window.jQuery)
</script>
<script>
    //$("pre code").append("<ol></ol>");
    $(document).ready(function () {
        //$("pre").addClass("hljs");
        $("pre").addClass("prettyprint linenums");
        //$('h2').addClass('header2');
        $('.header').on('click', accordion);


        //$("pre code ol").append('<ol></ol>');
        //$("pre .language-php span").replaceWith(function(index,oldHtml){
        //   return $("<li>").html(oldHTML);
        //});
        /*
        var varHtml = "";
        $("pre code").each(function (index, value) {

            varHtml = $(this).html();
            varHtml = '<ol>' + varHtml + '</ol>';
            $(this).html(varHtml);
        });
        $("pre code span").replaceWith(function (index, oldhtml) {
            at = $(this).attr("class");
            return $("<li>").html(oldhtml).addClass(at);
        });
        */
        //codeHtml = '<ol>'+codeHtml+'</ol>';

        //$("pre code").html(codeHtml);

    });

    function accordion() {
        $('.body_text').not($(this).next()).slideUp(1000);
        $(this).next().slideToggle(2000);
    }
</script>
<!--
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/highlight.min.js"></script>
<!-- and it's easy to individually load additional languages 
<script charset="UTF-8" src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/languages/go.min.js"></script>
<script type='text/javascript'>
    hljs.initHighlightingOnLoad();
</script>
-->
<style>
    ol.linenums {
        counter-reset: myCounter;
    }



    ol.linenums li {
        list-style: none;
        background-color: #282C34;
        color: #ccc;
    }

    ol.linenums li::before {
        position: absolute;
        display: block;
        counter-increment: myCounter;
        content: counter(myCounter);
        color: #474747;
        left: 15px;
        padding: 0;
        margin: 0;
        margin-top: 2px;
        background-color: #f78706;
        width: 35px;
        height: 20px;
        text-align: right;
    }

    

    pre {
        background-color: #858585;
    }

    ol.linenums li code {
        background-color: inherit;
    }

    ol.linenums li code .str,
    ol.linenums li code .pln {
        color: #98c379;
    }

    ol.linenums li code .pun,
    ol.linenums li code .typ {
        color: #abb2bf;
    }

    ol.linenums li code .lit {
        color: #d19a66;
    }

    ol.linenums li code .kwd {
        color: #61aeee;
    }
</style>