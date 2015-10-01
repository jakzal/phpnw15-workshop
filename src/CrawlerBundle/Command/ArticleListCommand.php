<?php

namespace CrawlerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArticleListCommand extends Command
{
    protected function configure()
    {
        $this->setName('article:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}