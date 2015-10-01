<?php

namespace Crawler;

interface ContentProvider
{
    /**
     * @param string $resource
     *
     * @return
     *
     * @throws \RuntimeException in case there are problems while fetching content
     */
    public function fetch($resource);
}