<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SaveProgressCommand extends Command
{
    private $exerciseCheckPoints = [
        'master',
        'exercise/crawler/1.1-article',
        'exercise/crawler/1.2-article-list-crawler',
        'exercise/crawler/1.3-article-extractor',
        'exercise/crawler/1.4-file-get-contents-content-provider',
        'exercise/crawler/1.5-article-list-crawler-factory',
        'exercise/crawler/1.6-article-list-command',
        'exercise/crawler/1.7-guzzle-content-provider',
        'exercise/crawler/1.8-solutions',
        'exercise/symfony-demo/1.1-repository-services',
        'exercise/symfony-demo/1.2-repository-query-methods',
        'exercise/symfony-demo/1.3-repository-update-methods',
        'exercise/symfony-demo/1.4-service-param-converter',
        'exercise/symfony-demo/1.5-user-provider',
        'exercise/symfony-demo/1.6-repository-methods',
        'exercise/symfony-demo/2.1-entities',
        'exercise/symfony-demo/3.1-repository-interfaces',
        'exercise/symfony-demo/3.2-repository-type-hints',
        'exercise/symfony-demo/3.3-repositories-in-infrastructure',
        'exercise/symfony-demo/4.1-repository-aliases',
        'exercise/symfony-demo/4.2-pdo-implementation',
        'exercise/symfony-demo/4.3-enable-pdo',
        'exercise/symfony-demo/5.1-enable-doctrine-cache',
        'exercise/symfony-demo/5.2-blog-post-cache',
        'exercise/symfony-demo/5.3-cache-invalidation',
    ];

    protected function configure()
    {
        $this->setName('save');
        $this->setDescription('Saves the exercise and moves on to the next one');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentBranch = $this->getCurrentBranch();
        $nextBranch = $this->findNextBranch($currentBranch);
        $workBranch = $this->getWorkBranch($currentBranch);

        $output->writeln(sprintf('You are currently on <info>%s</info>', $currentBranch));
        $output->writeln(sprintf('I will first save results of your work to <info>%s</info>', $workBranch));
        $output->writeln(sprintf('Next, I will switch to <info>%s</info>', $nextBranch));

        if (!$this->getConfirmation($input, $output)) {
            $output->writeln('<error>Aborted.</error>');

            return;
        }

        if ($this->workNeedsSaving()) {
            $this->saveWork($workBranch);
        } else {
            $output->writeln('There is nothing to save');
        }
        $this->switchBranch($nextBranch);
        $this->verifyOnBranch($nextBranch);

        $output->writeln('<info>Done.</info>');
    }

    private function getCurrentBranch()
    {
        return trim($this->exec('git branch | grep "^\\*" | sed -e "s/\\* //g"'));
    }

    private function findNextBranch($currentBranch)
    {
        $current = array_search($currentBranch, $this->exerciseCheckPoints);

        if (false === $current || !isset($this->exerciseCheckPoints[$current+1])) {
            throw new \InvalidArgumentException('Your trainer has failed to predict this situation');
        }

        return $this->exerciseCheckPoints[$current + 1];
    }

    private function getWorkBranch($currentBranch)
    {
        return 'my-'.$currentBranch;
    }

    private function workNeedsSaving()
    {
        return !preg_match('/working directory clean/smi', $this->exec('git status'));
    }

    private function saveWork($workBranch)
    {
        $this->exec(sprintf('git checkout -b %s', escapeshellarg($workBranch)));
        $this->exec('git add -A');

        try {
            $this->exec('git commit -m "Save progress"');
        } catch (\Exception $e) {
            if (false === strpos($e->getMessage(), 'nothing to commit')) {
                throw $e;
            }
        }
    }

    private function switchBranch($nextBranch)
    {
        $this->exec(sprintf('git checkout %s', escapeshellarg($nextBranch)));
    }

    private function verifyOnBranch($branch)
    {
        $currentBranch = $this->getCurrentBranch();

        if ($branch !== $currentBranch) {
            throw new \RuntimeException(sprintf('Expected to be on "%s" but found "%s"', $branch, $currentBranch));
        }
    }

    private function exec($command)
    {
        $output = exec($command, $o, $result);

        if ($result !== 0) {
            throw new \RuntimeException(sprintf('Command "%s" failed with: %s', $command, $output));
        }

        return $output;
    }

    private function getConfirmation(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Shall I go ahead and do it? ', false);

        return $helper->ask($input, $output, $question);
    }
}
