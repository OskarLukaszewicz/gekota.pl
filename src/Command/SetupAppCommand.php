<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetupAppCommand extends Command
{
    protected static $defaultName = 'app:setup';

    protected function configure()
    {
        $this
            ->setDescription('Setup the application')
            ->addOption('install', null, InputOption::VALUE_NONE, 'Run composer install')
            ->addOption('create-database', null, InputOption::VALUE_NONE, 'Create the database')
            ->addOption('make-migration', null, InputOption::VALUE_NONE, 'Generate a migration')
            ->addOption('run-migration', null, InputOption::VALUE_NONE, 'Run migrations')
            ->addOption('load-fixtures', null, InputOption::VALUE_NONE, 'Load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $appRootDirectory = $this->getApplication()->getKernel()->getProjectDir();
        $migrationsDirectory = $appRootDirectory . '/migrations';

        $filesystem = new Filesystem();
        if (!$filesystem->exists($migrationsDirectory)) {
            $filesystem->mkdir($migrationsDirectory);
            $io->success('Created migrations directory in the app root directory.');
        }

        if ($input->getOption('install')) {
            $io->success('Running composer install...');
            passthru('composer install');
        }

        if ($input->getOption('create-database')) {
            $io->success('Creating the database...');
            passthru('php bin/console doctrine:database:create');
        }

        if ($input->getOption('make-migration')) {
            $io->success('Generating a migration...');
            passthru('php bin/console make:migration');
        }

        if ($input->getOption('run-migration')) {
            $io->success('Running migrations...');
            passthru('php bin/console doctrine:migrations:migrate');
        }

        if ($input->getOption('load-fixtures')) {
            $io->success('Loading fixtures...');
            passthru('php bin/console doctrine:fixtures:load');
        }

        return Command::SUCCESS;
    }
}