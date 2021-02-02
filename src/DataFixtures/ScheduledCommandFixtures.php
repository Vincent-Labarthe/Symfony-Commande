<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

class ScheduledCommandFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $scheduledCommand = [];

        $classes =
            [
                '* 7-19 * * 1-7' => ['App\Command\ImportCsvCommande' => ['--path=./public/file/products.csv']]
            ];

        foreach ($classes as $cron => $items) {
            foreach ($items as $item) {
                foreach ($item as $class => $arguments) {
                    $scheduledCommand[] = [
                        'name' => constant($class . '::CONFIG')['title'],
                        'command' => constant($class . '::NAME'),
                        'arguments' => implode(' ', $arguments),
                        'cronExpression' => $cron,
                        'disabled' => true,
                        'execute_immediately' => false
                    ];
                }
            }
        }
        $this->save($manager, $scheduledCommand);
    }

    /**
     * Permet de sauvegarder en bdd des données
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Le gestionnaire d'entitée
     * @param array                                      $data    Tableau contenant les données à sauvegarder
     */
    private function save(ObjectManager $manager, array $data)
    {
        foreach ($data as $scheduledCommand) {
            $newScheduledCommand = new ScheduledCommand();
            $newScheduledCommand->setName($scheduledCommand['name']);
            $newScheduledCommand->setCommand($scheduledCommand['command']);
            $newScheduledCommand->setArguments($scheduledCommand['arguments']);
            $newScheduledCommand->setCronExpression($scheduledCommand['cronExpression']);
            $newScheduledCommand->setLogFile($scheduledCommand['logFile']);
            $newScheduledCommand->setPriority($scheduledCommand['priority']);
            $newScheduledCommand->setDisabled($scheduledCommand['disabled']);
            $newScheduledCommand->setExecuteImmediately($scheduledCommand['execute_immediately']);
            $manager->persist($newScheduledCommand);
        }
        $manager->flush();
    }
}