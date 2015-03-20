<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TutorTypeControllerTest
 */
class TutorTypeControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/type/tutor/', $users);
    }

    /**
     * @inheritdoc
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_tutor_type';
        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/type/tutor/')
            ->setFormData([
                $formName.'[name]'  => 'Test Tutor Type One',
            ])
            ->setCheckBoxes([
                $formName.'[default]'  => true,
            ])
            ->setFixedFormData([
                $formName.'[name]'  => 'xtest',
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
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]'  => 'Test Tutor Type One',
            ])
            ->setCheckBadEditFunction(function ($formValues) use ($formName) {
                $this->assertNotEquals(
                    'Test Tutor Type One',
                    $formValues[$formName.'[name]'],
                    'Form appears to have allowed us updated to a Duplicate Tutor Type name' .
                    ' - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\TutorType object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find TutorType entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
