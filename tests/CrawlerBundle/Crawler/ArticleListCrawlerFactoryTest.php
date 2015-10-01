<?php

namespace CrawlerBundle\Crawler;

use Crawler\ArticleListCrawler;

class ArticleListCrawlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItCreatesAnArticleListCrawler()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $factory = new ArticleListCrawlerFactory();

        $this->assertInstanceOf(ArticleListCrawler::class, $factory->create());
    }
}