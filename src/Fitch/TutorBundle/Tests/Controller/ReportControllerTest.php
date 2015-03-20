<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ReportControllerTest.
 */
class ReportControllerTest extends WebTestCase
{
    use AuthorisedClientTrait;

    /**
     * @inheritdoc
     */
    public function testAccess()
    {
        $users = [
            '' => 302,
            'xuser' => 200,
            'xdisabled' => 200,
            'xeditor' => 200,
            'xadmin' => 200,
            'xsuper' => 200,
        ];

        $this->checkAccess('GET', '/report', $users);
    }

    /**
     * Test the default Report (i.e. no form changes).
     */
    public function testDefaultReport()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        $crawler = $client->request('GET', '/report');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET '/report'"
        );

        $form = $crawler->selectButton('View Report')->form([]); //empty
        $crawler = $client->submit($form);

        // There should be three tutors, with matching regions, types and status
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Three")'));

        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Type One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Type Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Type Three")'));

        $this->assertCount(1, $crawler->filter('td:contains("Test Status One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Status Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Status Three")'));

        $this->assertCount(1, $crawler->filter('td:contains("Test Region One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Region Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Region Three")'));

        // there should be three skills
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type One (Test Level One)")'));
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type Two (Test Level Two)")'));
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type Three (Test Level Three)")'));

        // and two notes, emphasised
        $this->assertCount(1, $crawler->filter('em:contains("Test Note One")'));
        $this->assertCount(1, $crawler->filter('em:contains("Test Note Two")'));
    }

    /**
     * Test the removing a column.
     */
    public function testColumnSelection()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        $crawler = $client->request('GET', '/report');

        $form = $crawler->selectButton('View Report')->form([]);
        // Cant find a way to select these by ID - this isnt very flexible testing
        $form['ftbr[fields][2]']->untick();// tutor type
        $form['ftbr[fields][3]']->untick();// status
        $form['ftbr[fields][4]']->untick();// region

        $crawler = $client->submit($form);

        // There should be three tutors, with NO matching regions, types and status
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Tutor Type One")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Tutor Type Two")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Tutor Type Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Status One")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Status Two")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Status Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Region One")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Region Two")'));
        $this->assertCount(0, $crawler->filter('td:contains("Test Region Three")'));

        // there should be three skills
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type One (Test Level One)")'));
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type Two (Test Level Two)")'));
        $this->assertCount(1, $crawler->filter('li:contains("Test Competency Type Three (Test Level Three)")'));

        // and two notes, emphasised
        $this->assertCount(1, $crawler->filter('em:contains("Test Note One")'));
        $this->assertCount(1, $crawler->filter('em:contains("Test Note Two")'));
    }

    /**
     * Test Filtering by Region.
     */
    public function testRegionFilter()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        $crawler = $client->request('GET', '/report');

        $form = $crawler->selectButton('View Report')->form([]);
        $form['ftbr[operating_region][0]']->untick();// region 1
        $form['ftbr[operating_region][1]']->tick();// region 2
        $form['ftbr[operating_region][2]']->tick();// region 3

        $crawler = $client->submit($form);

        // There should be three tutors, with NO matching regions, types and status
        $this->assertCount(0, $crawler->filter('td:contains("Test Tutor One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Tutor Type One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Type Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Tutor Type Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Status One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Status Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Status Three")'));

        $this->assertCount(0, $crawler->filter('td:contains("Test Region One")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Region Two")'));
        $this->assertCount(1, $crawler->filter('td:contains("Test Region Three")'));
    }

    /**
     * Test saving Report.
     */
    public function testSavingReport()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');
        $crawler = $client->request('GET', '/report');
        $form = $crawler->selectButton('View Report')->form([]); //empty

        $crawler = $client->submit($form);

        // save this report
        $form = $crawler->selectButton('Create')->form([
            'fitch_tutorbundle_report[name]' => 'SavedReport',
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertCount(
            1,
            $crawler->filter('h2:contains("SavedReport")')
        );

        // Now try downloading an Excel
        $client->click($crawler->filter('a:contains("Download Excel")')->eq(0)->link());
        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'text/vnd.ms-excel; charset=utf-8'
        ));

        $this->assertTrue($response->headers->contains(
            'Content-Disposition',
            'attachment;filename=TrainerReport-SavedReport.xls'
        ));

        // Now try downloading a PDF
        $client->click($crawler->filter('a:contains("Download PDF")')->eq(0)->link());
        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'application/pdf'
        ));

        $this->assertTrue($response->headers->contains(
            'Content-Disposition',
            'attachment;filename=TrainerReport-SavedReport.pdf'
        ));

        // Now try downloading a CSV
        $client->click($crawler->filter('a:contains("Download CSV")')->eq(0)->link());
        $response = $client->getResponse();

        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'text/csv; charset=UTF-8'
        ));

        $this->assertTrue($response->headers->contains(
            'Content-Disposition',
            'attachment;filename=TrainerReport-SavedReport.csv'
        ));
    }
}
