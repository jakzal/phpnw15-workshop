<?php

namespace CrawlerBundle\Crawler;

use Crawler\ArticleListCrawler;

class ArticleListCrawlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItCreatesAnArticleListCrawler()
    {
        $factory = new ArticleListCrawlerFactory();

        $this->assertInstanceOf(ArticleListCrawler::class, $factory->create());
    }
}