<?php

class Article
{
    public $id;
    public $title;
    public $content;
    function view()
    {
        echo "<h1>$this->title</h1><p>$this->content</p>";
    }
}

$a = new Article();
echo $a->id;
$a->id = 1;
echo $a->id;