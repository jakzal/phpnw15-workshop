<?php

namespace Crawler;

class ArticleExtractor
{
    const ARTICLE_REGEXP = '#<article class="post">.*?<h2>.*?<a.*?href="(?P<url>.*?)".*?>(?P<title>.*?)</a>#smi';

    /**
     * @param string $content
     *
     * @return Article[]
     *
     * @throws \LogicException if no article is found in content
     */
    public function extractArticles($content)
    {
        if (preg_match_all(self::ARTICLE_REGEXP, $content, $matches)) {
            return array_map([$this, 'createArticle'], $matches['title'], $matches['url']);
        }

        throw new \LogicException(sprintf('Could not find articles in the content: "%s"', $content));
    }

    /**
     * @param string $title
     * @param string $url
     *
     * @return Article
     */
    private function createArticle($title, $url)
    {
        return new Article(trim($title), $url);
    }
}