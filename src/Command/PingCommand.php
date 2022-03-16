<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PingCommand extends Command
{
    protected static $defaultName = 'app:ping';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}
