<?php

namespace CrawlerBundle\Crawler;

use Crawler\ArticleExtractor;
use Crawler\ArticleListCrawler;
use Crawler\ContentProvider\FileGetContentsContentProvider;

class ArticleListCrawlerFactory
{
    /**
     * @return ArticleListCrawler
     */
    public function create()
    {
        return new ArticleListCrawler(
            new FileGetContentsContentProvider(),
            new ArticleExtractor()
        );
    }
}