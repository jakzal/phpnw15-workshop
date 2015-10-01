<?php

namespace Crawler;

interface ArticleExtractor
{
    const ARTICLE_REGEXP = '#<article class="post">.*?<h2>.*?<a.*?href="(?P<url>.*?)".*?>(?P<title>.*?)</a>#smi';

    /**
     * @param string $content
     *
     * @return Article[]
     *
     * @throws \LogicException if no article is found in content
     */
    public function extractArticles($content);
}