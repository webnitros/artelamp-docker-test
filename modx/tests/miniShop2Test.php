<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 21.03.2021
 * Time: 15:34
 */

use PHPUnit\Framework\TestCase;

class DemoTest extends MODxProcessorTestCase
{
    public function testSiteName()
    {
        self::assertEquals('REVOLUTION', $this->modx->getOption('site_name'));
    }

    public function testSiteStatus()
    {
        $site = (boolean)$this->modx->getOption('site_status');
        self::assertNOtTrue($site);
    }
}
