<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class OperatingRegionControllerTest
 */
class OperatingRegionControllerTest extends WebTestCase
{
    use AuthorisedClientTrait, CrudTestableTrait;

    /**
     * {@inheritdoc}
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

        $this->checkAccess('GET', '/admin/region/', $users);
    }

    /**
     * {@inheritdoc}
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_operatingregion';
        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/region/')
            ->setBadCreateFormData([
                $formName.'[name]'  => 'Test Region One',
                $formName.'[code]'  => 'xt',
                $formName.'[defaultCurrency]' => 1,
            ])
            ->setCheckBoxes([
                $formName.'[default]'  => true,
            ])
            ->setFixedCreateFormData([
                $formName.'[name]'  => 'xtest',
                $formName.'[code]'  => 'xt',
                $formName.'[defaultCurrency]' => 1,
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
                $formName.'[code]'  => 'xte',
                $formName.'[defaultCurrency]' => 2,
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]'  => 'Test Region One',
                $formName.'[code]'  => 'xt',
                $formName.'[defaultCurrency]' => 1,
            ])
            ->setCheckBadEditFunction(function ($formValues) use ($formName) {
                $this->assertNotEquals(
                    'Test Region One',
                    $formValues[$formName.'[name]'],
                    'Form appears to have allowed us updated to a Duplicate Region name' .
                    ' - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\OperatingRegion object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find OperatingRegion entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
