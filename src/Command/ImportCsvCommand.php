<?php

namespace App\Command;

use App\Service\CsvService;
use App\Service\ExecuteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportCsvCommand
 *
 * @package App\Command
 */
class ImportCsvCommand extends Command
{
    const NAME ='app:import-csv';
    const CONFIG = [
        'title' => "Import CSV File",
        'description' => "Permet d'importer et d'afficher un fichier csv",
        'frequence' => 'Toutes les heures entre 7h00 et 19h00',
        'arguments' => [
            'obligatoire' => ['Chemin du fichier csv à importer'],
            'optionnel' => ['json (permet d\'afficher en JSON)']
        ],
    ];

    /**
     * ExecuteService
     *
     * @var ExecuteService
     */
    private $executeService;

    /**
     * ImportCsvCommand constructor.
     *
     * @param CsvService $csvService Gestionnaire de service des fichiers CSV
     * @param ExecuteService $executeService le service d'execussion
     */
    public function __construct(CsvService $csvService, ExecuteService $executeService)
    {
        $this->executeService = $executeService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription(json_encode(self::CONFIG))
            ->addArgument('path', InputArgument::REQUIRED, 'Chemin du fichier csv à importer')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Afficher en JSON')
        ;
    }

    /**
     * Exécution de la commande
     *
     * @param InputInterface $input Point d'entrée
     * @param OutputInterface $output Point de sortie
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import of CSV...');
        try {
            $this->executeService->executeCmd($input, $output,$input->getArgument('path'));
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return 0;
    }
}
