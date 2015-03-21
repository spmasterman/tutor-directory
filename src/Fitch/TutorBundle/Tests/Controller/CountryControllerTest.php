<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CountryControllerTest.
 */
class CountryControllerTest extends WebTestCase
{
    use AuthorisedClientTrait, CrudTestableTrait;

    /**
     *
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

        $this->checkAccess('GET', '/admin/country/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_country';

        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/country/')
            ->setBadCreateFormData([
                $formName.'[name]'  => 'Test Country One',
                $formName.'[twoDigitCode]'  => 'xt',
                $formName.'[threeDigitCode]'  => 'xtt',
                $formName.'[dialingCode]'  => '+1',
                $formName.'[defaultRegion]' => 1,
            ])
            ->setCheckBoxes([
                $formName.'[preferred]'  => true,
                $formName.'[active]'  => true,
            ])
            ->setFixedCreateFormData([
                $formName.'[name]'  => 'xtest',
                $formName.'[twoDigitCode]'  => 'xt',
                $formName.'[threeDigitCode]'  => 'xtt',
                $formName.'[dialingCode]'  => '+1',
                $formName.'[defaultRegion]' => 1,
            ])
            ->setCheckCreateFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setFixedEditFormData([
                $formName.'[name]'  => 'xtest-edit',
                $formName.'[twoDigitCode]'  => 'xe',
                $formName.'[threeDigitCode]'  => 'xte',
                $formName.'[dialingCode]'  => '+44',
                $formName.'[defaultRegion]' => 2,
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]'  => 'Test Country One', //dupe
                $formName.'[twoDigitCode]'  => 'xe',
                $formName.'[threeDigitCode]'  => 'xte',
                $formName.'[dialingCode]'  => '+44',
            ])
            ->setCheckBadEditFunction(function ($formValues) {
                $this->assertNotEquals(
                    'Test Country One',
                    $formValues['fitch_tutorbundle_country[name]'],
                    'Form appears to have allowed us updated to a Duplicate country name - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\Country object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Country entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
