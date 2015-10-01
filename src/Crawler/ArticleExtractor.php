<?php

namespace Crawler;

interface ArticleExtractor
{
    /**
     * @param string $content
     *
     * @return Article[]
     *
     * @throws \LogicException if no article is found in content
     */
    public function extractArticles($content);
}