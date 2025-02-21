<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestableTrait;
use Fitch\CommonBundle\Tests\Controller\CrudTestConfig;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class LanguageControllerTest.
 */
class LanguageControllerTest extends WebTestCase
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

        $this->checkAccess('GET', '/admin/language/', $users);
    }

    /**
     * Test the CRUD interface.
     */
    public function testCompleteScenario()
    {
        $formName = 'fitch_tutorbundle_language';

        $crudTestConfig = new CrudTestConfig();
        $crudTestConfig
            ->setUser('xadmin')
            ->setUrl('/admin/language/')
            ->setBadCreateFormData([
                $formName.'[name]'  => 'Test Language One',
                $formName.'[threeLetterCode]'  => 'xtt',
            ])
            ->setCheckBoxes([
                $formName.'[preferred]'  => true,
                $formName.'[active]'  => true,
            ])
            ->setFixedCreateFormData([
                $formName.'[name]'  => 'xtest',
                $formName.'[threeLetterCode]'  => 'xtt',
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
                $formName.'[threeLetterCode]'  => 'xtt',
            ])
            ->setCheckEditFunction(function (Crawler $crawler) {
                $this->assertGreaterThan(
                    0,
                    $crawler->filter('[value="xtest-edit"]')->count(),
                    'Missing element [value="xtest-edit"]'
                );
            })
            ->setBadEditFormData([
                $formName.'[name]'  => 'Test Language One',
                $formName.'[threeLetterCode]'  => 'xtt',
            ])
            ->setCheckBadEditFunction(function ($formValues) use ($formName) {
                $this->assertNotEquals(
                    'Test Language One',
                    $formValues[$formName.'[name]'],
                    'Form appears to have allowed us updated to a Duplicate language name - please check the validators'
                );
            })
            ->setCheckDeletedFunction(function ($responseContent) {
                $this->assertNotRegExp('/xtest-edit/', $responseContent);
            })
            ->setCheckBadUpdateFunction(function (Crawler $crawler) {
                $exceptionThrown = ($crawler->filter('html:contains("NotFoundHttpException")')->count() > 0)
                    && ($crawler->filter(
                        'html:contains("Fitch\TutorBundle\Entity\Language object not found.")'
                    )->count() > 0);
                $this->assertTrue($exceptionThrown, "Exception thrown 'Unable to find Language entity'");
            });

        $this->performCrudTest($crudTestConfig);
    }
}
