<?php

namespace ride\cli\command\orm;

use ride\cli\command\AbstractCommand;
use ride\cli\service\FixtureCreator;
use ride\library\config\parser\JsonParser;
use ride\library\orm\OrmManager;
use ride\library\system\file\browser\FileBrowser;

class FixturesCommand extends AbstractCommand {

    protected function initialize() {

        $this->setDescription("Create Dummy entries");
    }

    public function invoke(OrmManager $orm, JsonParser $jsonParser, FileBrowser $fileBrowser, FixtureCreator $fixtureCreator) {
        $fixtureCreator->createFixtures($orm, $fileBrowser, $jsonParser);
    }
}