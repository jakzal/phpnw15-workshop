<?php

namespace Crawler;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testItExposesArticleProperties()
    {
        $article = new Article('Foo', '/foo.html');

        $this->assertSame('Foo', $article->getTitle());
        $this->assertSame('/foo.html', $article->getUrl());
    }
}