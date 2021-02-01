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
use Symfony\Component\Console\Question\Question;
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
        $helper = $this->getHelper('question');
//        $question = new Question('Quel est le mot de passe ?', '');
//        $pwd = $helper->ask($input, $output, $question);
//        if ($pwd !== 'agenceDnD'){
//            throw new \LogicException('Mot de passe incorrect');
//        }

        if ($arg1) {
            $io->comment(sprintf('L\'arguement passe est : %s', $arg1));
            $cvsFile = array_map(function($cvsFile) {return str_getcsv($cvsFile, ';'); }, file('%kernel.root_dir%/../'.$arg1));
            $data = $this->csvService->extractCSVData($cvsFile);

            foreach($data as $values){
                $title = array_keys($values);
            }

            $table = new Table($output);
            $table->setHeaders($title);
            $table->setRows($data);
            $table->render();
            //$io->success();
        }else{
            throw new \LogicException('le chemin du fichier est manquant');
        }

        if ($input->getOption('json')) {
            $io->note(sprintf('ok'));
        }

        $io->success('Le fichier csv a bien été importé');

        return 0;
    }
}
