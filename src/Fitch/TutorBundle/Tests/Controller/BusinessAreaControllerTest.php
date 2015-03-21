<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BusinessAreaControllerTest.
 */
class BusinessAreaControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/business_area/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_business_area';

        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/business_area/')
            ->setBadCreateFormData([
                $formName.'[name]' => 'Test Business Area 1',
                $formName.'[code]' => 'XX',
            ])
            ->setCheckBoxes([
                $formName.'[prependToCategoryName]' => true,
                $formName.'[displayAsCode]'  => true,
                $formName.'[default]'  => true,
            ])
            ->setFixedCreateFormData([
                $formName.'[name]' => 'xtest',
                $formName.'[code]' => 'XX',
            ])
            ->setCheckCreateFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setFixedEditFormData([
                $formName.'[name]' => 'xtest-edit',
                $formName.'[code]' => 'YY',
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]' => 'Test Business Area 1',
                $formName.'[code]' => 'YY',
            ])
            ->setCheckBadEditFunction(function ($formValues) {
                $this->assertNotEquals(
                    'Test Business Area 1',
                    $formValues['fitch_tutorbundle_business_area[name]'],
                    'Form appears to have allowed us updated to a Duplicate Business Area name '.
                    '- please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler
                            ->filter('html:contains("Fitch\TutorBundle\Entity\BusinessArea object not found.")')
                            ->count() > 0
                    );
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Business Area entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
