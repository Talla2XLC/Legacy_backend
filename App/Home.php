<?php

namespace App;

use Parsedown;

class Home
{
    public function index()
    {
?>

    
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

    code,
    pre {
        border-radius: 3px 3px 3px 3px;
        color: #333333;
        font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
        font-size: 12px;
        padding: 0 3px 2px;
    }

    code {
        background-color: #079cf1;
        border: 1px solid #4a05eb;
        color: #dae90d;
        padding: 2px 4px;
        white-space: nowrap;
        font-weight: bold;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12pt;
        border-radius: 5px;
    }

    pre {
        background-color: #F5F5F5;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px 4px 4px 4px;
        display: block;
        font-size: 13px;
        line-height: 20px;
        margin: 0 0 10px;
        padding: 9.5px;
        white-space: pre-wrap;
        word-break: break-all;
        word-wrap: break-word;
    }
    pre code{
        background-color: #F7F7F9;
        border: 1px solid #E1E1E8;
        color: #73178f;
        padding: 2px 4px;
        white-space: nowrap;
        font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
        font-size: 12px;
    }
    .body_text {
        display: none;
    }
    .header{
        cursor: pointer;
        border-bottom: 1px solid #454545;
    }
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
                                echo '<h2 class="header">'.$inDir.'</h2>';
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
<script>
    $(document).ready(function(){
        $("pre").addClass("prettyprint");
        //$('h2').addClass('header2');
        $('.header').on('click',accordion)
    });
    function accordion(){
        $('.body_text').not($(this).next()).slideUp(1000);
        $(this).next().slideToggle(2000);
    }
</script>
<script async src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>