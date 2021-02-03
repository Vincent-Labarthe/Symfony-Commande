<?php

namespace App\Service;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteService
{
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
        $this->csvService = $csvService;

    }

    /**
     * Permet d"executer les differentes commande d'import de CSV
     *
     * @param InputInterface  $input   Point d'entrée de la console
     * @param OutputInterface $output  Point de sortie de la console
     * @param string          $path    L'argument obligatoire
     *
     * @throws \Exception
     */
    public function executeCmd(InputInterface $input, OutputInterface $output, string $path)
    {
        $cvsFile = array_map(function ($cvsFile) {
            return str_getcsv($cvsFile, ';');
        }, file('%kernel.root_dir%/../' . $path));
        $data = $this->csvService->extractCSVData($cvsFile);

        //si l'option est passé on retourne de suite le JSON
        if ($input->getOption('json')) {
            $output->write(json_encode($data));
        } else {
            foreach ($data as $values) {
                $title = array_keys($values);
            }

            $table = new Table($output);
            $table->setHeaders($title);
            $table->setRows($data);
            $table->setStyle('borderless');
            $table->render();
        }
    }
}
