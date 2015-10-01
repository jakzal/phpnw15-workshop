<?php

namespace Crawler;

class ArticleExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Could not find articles in the content:
     */
    public function testItThrowsAnExceptionIfNoArticleWasFound()
    {
        $content = '<article><h2><a href="/en/blog/posts/foo">Foo</a></h2></article>';

        $articleExtractor = new ArticleExtractor();
        $articleExtractor->extractArticles($content);
    }

    public function testItReturnsListOfArticles()
    {
        $content =
            '<article class="post"><h2><a href="/en/blog/posts/foo">Foo</a></h2></article>
             <article class="post"><h2><a href="/en/blog/posts/bar">Bar</a></h2></article>';

        $articleExtractor = new ArticleExtractor();
        $articles = $articleExtractor->extractArticles($content);

        $this->assertInternalType('array', $articles);
        $this->assertEquals([new Article('Foo', '/en/blog/posts/foo'), new Article('Bar', '/en/blog/posts/bar')], $articles);
    }

    public function testItTrimsTheTitles()
    {
        $content =
            '<article class="post"><h2><a href="/en/blog/posts/foo">
                Foo
            </a></h2></article>';

        $articleExtractor = new ArticleExtractor();
        $articles = $articleExtractor->extractArticles($content);

        $this->assertInternalType('array', $articles);
        $this->assertEquals([new Article('Foo', '/en/blog/posts/foo')], $articles);
    }
}