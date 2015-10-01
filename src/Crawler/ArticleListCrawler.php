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
        try {
            $content = $this->contentProvider->fetch($resource);

            return $this->articleExtractor->extractArticles($content);
        } catch (\LogicException $e) {
            throw new \LogicException(sprintf('Could not find articles in the resource: "%s".', $resource), 0, $e);
        } catch (\Exception $e) {
            throw new \LogicException(sprintf('Could not fetch the resource: "%s".', $resource), 0, $e);
        }
    }
}