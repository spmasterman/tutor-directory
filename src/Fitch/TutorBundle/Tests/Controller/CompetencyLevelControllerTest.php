<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CompetencyLevelControllerTest extends WebTestCase
{
    use AuthorisedClientTrait, CrudTestableTrait;

    /**
     * Perform Access Tests
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
     * Test the CRUD interface
     */
    public function testCompleteScenario()
    {
        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/country/')
            ->setFormData([
                'fitch_tutorbundle_country[name]'  => 'Test Country One',
                'fitch_tutorbundle_country[twoDigitCode]'  => 'xt',
                'fitch_tutorbundle_country[threeDigitCode]'  => 'xtt',
                'fitch_tutorbundle_country[dialingCode]'  => '+1',
                'fitch_tutorbundle_country[defaultRegion]' => 1,
            ])
            ->setCheckBoxes([
                'fitch_tutorbundle_country[preferred]'  => true,
                'fitch_tutorbundle_country[active]'  => true,
            ])
            ->setFixedFormData([
                'fitch_tutorbundle_country[name]'  => 'xtest',
                'fitch_tutorbundle_country[twoDigitCode]'  => 'xt',
                'fitch_tutorbundle_country[threeDigitCode]'  => 'xtt',
                'fitch_tutorbundle_country[dialingCode]'  => '+1',
                'fitch_tutorbundle_country[defaultRegion]' => 1,
            ])
            ->setCheckAdditionFunction(function(Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('td:contains("xtest")')->count(),
                    'Missing element td:contains("Test")'
                );
            })
            ->setEditFormData([
                'fitch_tutorbundle_country[name]'  => 'xtest-edit',
                'fitch_tutorbundle_country[twoDigitCode]'  => 'xe',
                'fitch_tutorbundle_country[threeDigitCode]'  => 'xte',
                'fitch_tutorbundle_country[dialingCode]'  => '+44',
                'fitch_tutorbundle_country[defaultRegion]' => 2,
            ])
            ->setCheckEditFunction(function(Crawler $crawler){
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                'fitch_tutorbundle_country[name]'  => 'Test Country One', //dupe
                'fitch_tutorbundle_country[twoDigitCode]'  => 'xe',
                'fitch_tutorbundle_country[threeDigitCode]'  => 'xte',
                'fitch_tutorbundle_country[dialingCode]'  => '+44',
            ])
            ->setCheckBadEditFunction(function($formValues){
                $this->assertNotEquals(
                    'Test Country One',
                    $formValues['fitch_tutorbundle_country[name]'],
                    'Form appears to have allowed us updated to a Duplicate country name - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function(Crawler $crawler){
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter('html:contains("Fitch\TutorBundle\Entity\Country object not found.")')->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Country entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
