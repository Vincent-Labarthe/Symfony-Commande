<?php

namespace App\Command;

use App\Service\CsvService;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportCsvCommand extends Command
{
    protected static $defaultName = 'app:import-csv';

    /**
     * @var CsvService Gestionnaire de service des fichiers CSV
     */
    private $csvService;

    /**
     * ImportCsvCommand constructor.
     *
     * @param CsvService $csvService Gestionnaire de service des fichiers CSV
     */
    public function __construct(CsvService $csvService)
    {
        $this->csvService =$csvService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Affiche une grille d\'information à partir d\'un ficher csv')
            ->addArgument('path', InputArgument::OPTIONAL, 'chemin du fichier csv à importer')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Afficher en JSON')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting import of CSV...');

        $arg1 = $input->getArgument('path');

        if ($arg1) {
            $io->note(sprintf('L\'arguement passé est : %s', $arg1));
            $rows = array_map(function($row) {return str_getcsv($row, ';'); }, file('%kernel.root_dir%/../'.$arg1));
            $data = $this->csvService->extractCSVData($rows);
            $table = new Table($output);
            $rows[0][] ='slug';
            $table->setHeaders($rows[0]);

            $table->render();
            //$io->success();
        }else{
            throw new \LogicException('le chemin du fichier est manquant');

        }

        if ($input->getOption('json')) {
            $io->note(sprintf('ok'));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
