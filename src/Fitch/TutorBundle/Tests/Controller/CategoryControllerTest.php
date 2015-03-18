<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/category/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_category';

        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/category/')
            ->setFormData([
                $formName.'[name]' => 'Test Category One',
                $formName.'[businessArea]' => 1,
            ])
            ->setCheckBoxes([
                'fitch_tutorbundle_category[default]' => true,
            ])
            ->setFixedFormData([
                $formName.'[name]' => 'xtest',
                $formName.'[businessArea]' => 1,
            ])
            ->setCheckAdditionFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setEditFormData([
                $formName.'[name]' => 'xtest-edit',
                $formName.'[businessArea]' => 2,
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]' => 'Test Category One',
                $formName.'[businessArea]' => 1,
            ])
            ->setCheckBadEditFunction(function ($formValues) {
                $this->assertNotEquals(
                    'Test Category One',
                    $formValues['fitch_tutorbundle_category[name]'],
                    'Form appears to have allowed us updated to a Duplicate Category name - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                            'html:contains("Fitch\TutorBundle\Entity\Category object not found.")'
                        )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Category entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
