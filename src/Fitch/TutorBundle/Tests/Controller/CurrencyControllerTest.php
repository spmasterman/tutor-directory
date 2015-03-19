<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CurrencyControllerTest.
 */
class CurrencyControllerTest extends WebTestCase
{
    use AuthorisedClientTrait, CrudTestableTrait;

    /**
     * @inheritdoc
     */
    public function testAccess()
    {
        $users = [
            '' => 302,
            'xuser' => 403,
            'xdisabled' => 403,
            'xeditor' => 403,
            'xadmin' => 200,
            'xsuper' => 200,
        ];

        $this->checkAccess('GET', '/admin/currency/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_currency';

        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/currency/')
            ->setFormData([
                $formName.'[name]'  => 'Test Currency One',
                $formName.'[threeDigitCode]'  => 'xtt',
            ])
            ->setCheckBoxes([
                $formName.'[preferred]'  => false,
                $formName.'[active]'  => false,
            ])
            ->setFixedFormData([
                $formName.'[name]'  => 'xtest',
                $formName.'[threeDigitCode]'  => 'xtt',
            ])
            ->setCheckAdditionFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setEditFormData([
                $formName.'[name]'  => 'xtest-edit',
                $formName.'[threeDigitCode]'  => 'abc',
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]'  => 'Test Currency One',
                $formName.'[threeDigitCode]'  => 'xtt',
            ])
            ->setCheckBadEditFunction(function ($formValues) {
                $this->assertNotEquals(
                    'Test Currency One',
                    $formValues['fitch_tutorbundle_currency[name]'],
                    'Form appears to have allowed us updated to a Duplicate currency name'.
                    ' - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\Currency object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Currency entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
