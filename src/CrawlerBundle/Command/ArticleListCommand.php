<?php

namespace CrawlerBundle\Command;

use CrawlerBundle\Crawler\ArticleListCrawlerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ArticleListCommand extends Command
{
    protected function configure()
    {
        $this->setName('article:list');
        $this->addOption('resource', null, InputOption::VALUE_REQUIRED, 'The article list resource', 'http://127.0.0.1:8000/en/blog/');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = (new ArticleListCrawlerFactory())->create();
        $articles = $crawler->findArticles($input->getOption('resource'));

        foreach ($articles as $article) {
            $output->writeln(sprintf('<info>%s</info> %s', $article->getTitle(), $article->getUrl()));
        }
    }
}