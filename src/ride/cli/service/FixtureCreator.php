<?php

namespace ride\cli\service;

use Faker\Factory;
use ride\library\config\parser\JsonParser;
use ride\library\orm\OrmManager;
use ride\library\system\file\browser\FileBrowser;

class FixtureCreator {

    /**
     * Function to Create Random content for Data Types
     * @param OrmManager $orm
     * @param FileBrowser $fileBrowser
     * @param JsonParser $jsonParser
     * @throws \ride\library\config\exception\ConfigException
     * @throws \ride\library\orm\exception\OrmException
     * @throws \ride\library\system\exception\FileSystemException
     */
    public function createFixtures(OrmManager $orm, FileBrowser $fileBrowser, JsonParser $jsonParser) {
        $filePath = $fileBrowser->getApplicationDirectory()->getChild('config')->getChild('fixtures.json');
        $fixturesFile = $filePath->read();
        $parsedFile = $jsonParser->parseToPhp($fixturesFile);
        $faker = Factory::create($parsedFile['locale']);
        foreach ($parsedFile['fixtures'] as $fixture) {
            $entryModel = $orm->getModel($fixture['entry']);
            for ($i = 0; $i < $fixture['amount']; $i++) {

                    $entry = $entryModel->createEntry();
                    foreach ($fixture['fields'] as $index => $field) {
                        if (!is_array($field)) {
                            $entry->$index = $faker->$field;
                        } else {
                            $relationModel = $orm->getModel($field['entry']);

                            if (isset($field['fields'])) {
                                if (isset($field['amount']) || $field['amount'] == 1) {
                                    $relationEntry = $relationModel->createEntry();

                                    foreach ($field['fields'] as $entryIndex => $entryField) {
                                        $relationEntry->$entryIndex = $entryField;
                                    }
                                    $entry->$index = $relationEntry;
                                }
                                elseif (isset($field['amount']) && $field['amount'] > 1) {
                                    foreach ($field['fields'] as $entryIndex => $entryField) {
                                        $relationEntry = $relationModel->createEntry();
                                    }
                                }
                            }
                            else {
                                if ($field['amount'] == 1 && !isset($field['vocabulary'])) {
                                    $relationEntry = $relationModel->createQuery()->queryFirst();
                                } elseif ($field['amount'] == 1 && isset($field['vocabulary'])){
                                    $query = $relationModel->createQuery();
                                    $query->addCondition('{vocabulary} = %1%', $field['vocabulary']);
                                    $relationEntry = $query->queryFirst();
                                }
                                elseif ($field['amount'] > 1 && isset($field['vocabulary'])) {
                                    $query = $relationModel->createQuery();
                                    $query->addCondition('{vocabulary} = %1%', $field['vocabulary']);
                                    $relationEntry = $query->query();
                                }
                                else {
                                    $query = $relationModel->createQuery();
                                    $query->setLimit($field['amount']);
                                    $relationEntry = $query->query();
                                }
                            }
                        $entry->$index = $relationEntry;
                        }
                    }
                    $entryModel->save($entry);
            }
        }

    }
    
}