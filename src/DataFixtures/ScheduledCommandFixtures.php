<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

/**
 * Class ScheduledCommandFixtures
 *
 * @package App\DataFixtures
 */
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
                '0 9-17 * * *' => ['App\Command\ImportCsvCommande' => ['--path=./public/file/products.csv']]
            ];

        foreach ($classes as $cron => $items) {
            foreach ($items as $item) {
                foreach ($item as $class => $arguments) {
                    $scheduledCommand[] = [
                        'name' => constant($class . '::CONFIG')['title'],
                        'command' => constant($class . '::NAME'),
                        'arguments' => implode((array)' ', $arguments),
                        'cronExpression' => $cron,
                        'priority'=>'1',
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
     * @param ObjectManager $manager Le gestionnaire d'entitée
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
            $newScheduledCommand->setPriority($scheduledCommand['priority']);
            $newScheduledCommand->setDisabled($scheduledCommand['disabled']);
            $newScheduledCommand->setExecuteImmediately($scheduledCommand['execute_immediately']);
            $manager->persist($newScheduledCommand);
        }
        $manager->flush();
    }
}
