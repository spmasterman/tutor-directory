<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompetencyLevelControllerTest
 */
class CompetencyLevelControllerTest extends WebTestCase
{
    use AuthorisedClientTrait, CrudTestableTrait;

    /**
     * Perform Access Tests.
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

        $this->checkAccess('GET', '/admin/level/competency/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/level/competency/')
            ->setFormData([
                'fitch_tutorbundle_competencylevel[name]'  => 'Test Level One',
                'fitch_tutorbundle_competencylevel[color]'  => '#cccccc',
            ])
            ->setCheckBoxes([])
            ->setFixedFormData([
                'fitch_tutorbundle_competencylevel[name]'  => 'xtest',
                'fitch_tutorbundle_competencylevel[color]'  => '#cccccc',
            ])
            ->setCheckAdditionFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setEditFormData([
                'fitch_tutorbundle_competencylevel[name]'  => 'xtest-edit',
                'fitch_tutorbundle_competencylevel[color]'  => '#db8c8b',
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                'fitch_tutorbundle_competencylevel[name]'  => 'Test Level One',
                'fitch_tutorbundle_competencylevel[color]'  => '#cccccc',
            ])
            ->setCheckBadEditFunction(function ($formValues) {
                $this->assertNotEquals(
                    'Test Level One',
                    $formValues['fitch_tutorbundle_competencylevel[name]'],
                    'Form appears to have allowed us updated to a Duplicate CompetencyLevel name' .
                    ' - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\CompetencyLevel object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find CompetencyLevel entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
