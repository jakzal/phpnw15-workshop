<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SaveProgressCommand extends Command
{
    private $exerciseCheckPoints = [
        'php-summercamp',
        'workshop/1.1-repository-services',
        'workshop/1.2-repository-query-methods',
        'workshop/1.3-repository-update-methods',
        'workshop/1.4-service-param-converter',
        'workshop/1.5-user-provider',
        'workshop/1.6-repository-methods',
        'workshop/2.1-entities',
        'workshop/3.1-repository-interfaces',
        'workshop/3.2-repository-type-hints',
        'workshop/3.3-repositories-in-infrastructure',
        'workshop/4.1-repository-aliases',
        'workshop/4.2-pdo-implementation',
        'workshop/4.3-enable-pdo',
        'workshop/5.1-enable-doctrine-cache',
        'workshop/5.2-blog-post-cache',
        'workshop/5.3-cache-invalidation',
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

        $this->saveWork($workBranch);
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
