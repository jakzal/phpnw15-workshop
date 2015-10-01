<?php

namespace Crawler\ContentProvider;

use Crawler\ContentProvider;
use GuzzleHttp\Client;

/**
 * @group integration
 */
class GuzzleContentProviderTest extends \PHPUnit_Framework_TestCase
{
    const HOST = 'http://127.0.0.1:8000/';

    private $contentProvider;

    public function setUp()
    {
        $headers = @get_headers(self::HOST);

        if (!$headers) {
            $this->markTestSkipped('The PHP server is not running on ', self::HOST);
        }

        $this->contentProvider = new GuzzleContentProvider(new Client());
    }

    public function testItIsAContentProvider()
    {
        $this->assertInstanceOf(ContentProvider::class, $this->contentProvider);
    }

    public function testItLoadsAResource()
    {
        $this->assertRegExp('/<body id="homepage">/', $this->contentProvider->fetch(self::HOST));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Failed to fetch a resource:
     */
    public function testItThrowsRuntimeExceptionIfGuzzleFailsToFetchContent()
    {
        $this->contentProvider->fetch(self::HOST.'/foobar.html');
    }
}