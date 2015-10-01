<?php

namespace CrawlerBundle\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group integration
 */
class ArticleListCommandTest extends \PHPUnit_Framework_TestCase
{
    const HOST = 'http://127.0.0.1:8000/';

    protected function setUp()
    {
        $headers = @get_headers(self::HOST);

        if (!$headers) {
            $this->markTestSkipped('The PHP server is not running on ', self::HOST);
        }
    }

    public function testExecuteReturnsZeroStatusCodeIfSuccessful()
    {
        $commandTester = $this->executeCommand();

        $this->assertSame(0, $commandTester->getStatusCode());
    }

    public function testItListsFoundArticles()
    {
        $commandTester = $this->executeCommand();

        $this->assertRegExp('/Morbi tempus commodo mattis/smi', $commandTester->getDisplay());
    }

    /**
     * @return CommandTester
     */
    public function executeCommand()
    {
        $application = new Application();
        $application->add(new ArticleListCommand());

        $command = $application->find('article:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()], ['env' => 'test']);

        return $commandTester;
    }
}