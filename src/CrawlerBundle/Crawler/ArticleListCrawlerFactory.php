<?php

namespace CrawlerBundle\Crawler;

use Crawler\ArticleExtractor;
use Crawler\ArticleListCrawler;
use Crawler\ContentProvider\GuzzleContentProvider;
use GuzzleHttp\Client;

class ArticleListCrawlerFactory
{
    /**
     * @return ArticleListCrawler
     */
    public function create()
    {
        return new ArticleListCrawler(
            new GuzzleContentProvider(new Client()),
            new ArticleExtractor()
        );
    }
}