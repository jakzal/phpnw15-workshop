<?php

namespace Crawler\ContentProvider;

use Crawler\ContentProvider;

class FileGetContentsContentProvider implements ContentProvider
{
    /**
     * @param string $resource
     *
     * @return
     *
     * @throws \RuntimeException in case there are problems while fetching content
     */
    public function fetch($resource)
    {
    }
}