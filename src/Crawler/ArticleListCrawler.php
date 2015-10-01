<?php

namespace Crawler;

class ArticleListCrawler
{
    /**
     * @var ContentProvider
     */
    private $contentProvider;

    /**
     * @var ArticleExtractor
     */
    private $articleExtractor;

    /**
     * @param ContentProvider $contentProvider
     * @param ArticleExtractor $articleExtractor
     */
    public function __construct(ContentProvider $contentProvider, ArticleExtractor $articleExtractor)
    {
        $this->contentProvider = $contentProvider;
        $this->articleExtractor = $articleExtractor;
    }

    /**
     * @param string $resource
     *
     * @return Article[]
     */
    public function findArticles($resource)
    {
        $content = $this->contentProvider->fetch($resource);

        return $this->articleExtractor->extractArticles($content);
    }
}