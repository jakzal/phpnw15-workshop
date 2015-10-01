<?php

namespace Crawler;

class ArticleListCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testItExtractsArticlesFromFetchedContent()
    {
        $articles = [new Article('Foo', 'foo.html')];

        $contentProvider = $this->prophesize(ContentProvider::class);
        $contentProvider->fetch('resource.html')->willReturn('<article/>');

        $articleExtractor = $this->prophesize(ArticleExtractor::class);
        $articleExtractor->extractArticles('<article/>')->willReturn($articles);

        $articleListCrawler = new ArticleListCrawler($contentProvider->reveal(), $articleExtractor->reveal());
        $returnedArticles = $articleListCrawler->findArticles('resource.html');

        $this->assertSame($articles, $returnedArticles);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Could not find articles in the resource: "resource.html"
     */
    public function testItThrowsAnExceptionIfNoArticleWasFound()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $contentProvider = $this->prophesize(ContentProvider::class);
        $contentProvider->fetch('resource.html')->willReturn('<article/>');

        $articleExtractor = $this->prophesize(ArticleExtractor::class);
        $articleExtractor->extractArticles('<article/>')->willThrow(new \LogicException());

        $articleListCrawler = new ArticleListCrawler($contentProvider->reveal(), $articleExtractor->reveal());
        $articleListCrawler->findArticles('resource.html');
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Could not fetch the resource: "resource.html"
     */
    public function testItThrowsAnExceptionIfResourceCouldNotBeFetched()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $contentProvider = $this->prophesize(ContentProvider::class);
        $contentProvider->fetch('resource.html')->willThrow(new \Exception());

        $articleExtractor = $this->prophesize(ArticleExtractor::class);
        $articleExtractor->extractArticles('<article/>')->willReturn([new Article('Foo', 'foo.html')]);

        $articleListCrawler = new ArticleListCrawler($contentProvider->reveal(), $articleExtractor->reveal());
        $articleListCrawler->findArticles('resource.html');
    }
}