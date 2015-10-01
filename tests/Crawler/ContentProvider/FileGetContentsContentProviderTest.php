<?php

namespace Crawler\ContentProvider;

use Crawler\ContentProvider;

/**
 * @group integration
 */
class FileGetContentsContentProviderTest extends \PHPUnit_Framework_TestCase
{
    private $resource;

    const CONTENT = 'FooBar2015';

    public function setUp()
    {
        if (null !== $this->resource && file_exists($this->resource)) {
            unlink($this->resource);
        }

        $this->resource = tempnam(sys_get_temp_dir(), 'test_');
        file_put_contents($this->resource, self::CONTENT);
    }

    public function testItIsAContentProvider()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $this->assertInstanceOf(ContentProvider::class, new FileGetContentsContentProvider());
    }

    public function testItLoadsAResource()
    {
        $this->markTestSkipped('This test is waiting to be implemented.');

        $contentProvider = new FileGetContentsContentProvider();

        $this->assertSame(self::CONTENT, $contentProvider->fetch($this->resource));
    }
}