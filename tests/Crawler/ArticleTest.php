<?php

namespace Crawler;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testItExposesArticleProperties()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $article = new Article('Foo', '/foo.html');

        $this->assertSame('Foo', $article->getTitle());
        $this->assertSame('/foo.html', $article->getUrl());
    }
}