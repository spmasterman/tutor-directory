<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\TutorBundle\Model\ReportDefinition;

/**
 * Test the creation of ReportDefinition objects. None of this tests the SQL that gets generated FROM the definition,
 * just that the definition is well formed when it gets sent to the Entity Manager / Repository
 *
 * Class ReportDefinitionTest
 * @package Fitch\TutorBundle\Tests\Model
 */
class ReportDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleFilters()
    {
        $filters = [
            'tutor_type' => 'TutorType',
            'status' => 'Status',
            'operating_region' => 'Region',
            'competencyLevel' => 'CompetencyLevel',
        ];

        foreach ($filters as $formField => $filterKey) {
            $identityEntity = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
            $identityEntity->expects($this->once())->method('getId')->willReturn(1);

            $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
            $form->expects($this->any())->method('getData')->willReturn([
                $formField => [$identityEntity],
                'fields' => [$formField],
            ]);

            $reportDefinition = new ReportDefinition($form, false);
            $this->assertTrue($reportDefinition->isFilteredBy($filterKey));
            $this->assertFalse($reportDefinition->isFilteredBy('SOMETHING FALSE'));
            foreach ($filters as $otherFilterKey) {
                if ($otherFilterKey != $filterKey) {
                    $this->assertFalse($reportDefinition->isFilteredBy($otherFilterKey));
                }
            }
        }
    }

    public function testNestedIdFilters()
    {
        $filters = [
            'Language' => ['language','language'],
            'Category' => ['category','category'],
            'CompetencyType' => ['competency', 'competencyType'],
        ];

        foreach ($filters as $filterKey => $innerOuter) {
            $identityEntity = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
            $identityEntity->expects($this->once())->method('getId')->willReturn(1);

            $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
            $form->expects($this->any())->method('getData')->willReturn([
                $innerOuter[0] => [$innerOuter[1] => [$identityEntity]],
                'fields' => [$innerOuter[0]],
            ]);

            $reportDefinition = new ReportDefinition($form, false);
            $this->assertTrue($reportDefinition->isFilteredBy($filterKey));
            $this->assertFalse($reportDefinition->isFilteredBy('SOMETHING FALSE'));
        }
    }

    public function testLanguageDefaultOperatorFilter()
    {
        $identityEntityOne = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityOne->expects($this->once())->method('getId')->willReturn(1);

        $identityEntityTwo = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityTwo->expects($this->once())->method('getId')->willReturn(2);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'language' => ['language' => [$identityEntityOne, $identityEntityTwo]],
            'fields' => ['language'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);
        // relying on language operator default)
        $this->assertEquals($reportDefinition->getLanguageOperator(), 'and');
        $this->assertTrue($reportDefinition->isFilteredBy('Language'));
    }

    public function testNoLanguageSelectedFilter()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'language' => ['language' => []],
            'fields' => ['language'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);
        // relying on language operator default)
        $this->assertFalse($reportDefinition->isFilteredBy('Language'));
    }

    public function testLanguageCombine()
    {
        $identityEntityOne = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityOne->expects($this->once())->method('getId')->willReturn(1);

        $identityEntityTwo = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityTwo->expects($this->once())->method('getId')->willReturn(2);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'language' => [
                'language' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'fields' => ['language'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);
        // relying on language operator default)
        $this->assertEquals($reportDefinition->getLanguageOperator(), 'or');
        $this->assertContains(1, $reportDefinition->getLanguageIds());
        $this->assertContains(2, $reportDefinition->getLanguageIds());
        $this->assertCount(2, $reportDefinition->getLanguageIds());
    }

    public function testCategoryCombine()
    {
        $identityEntityOne = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityOne->expects($this->once())->method('getId')->willReturn(1);

        $identityEntityTwo = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityTwo->expects($this->once())->method('getId')->willReturn(2);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'category' => [
                'category' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'fields' => ['category'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);
        // relying on language operator default)
        $this->assertEquals($reportDefinition->getCategoryOperator(), 'or');
        $this->assertContains(1, $reportDefinition->getCategoryIds());
        $this->assertContains(2, $reportDefinition->getCategoryIds());
        $this->assertCount(2, $reportDefinition->getCategoryIds());
    }

    public function testRate()
    {
        $currencyEntity = $this->getMockBuilder('Fitch\TutorBundle\Entity\Currency')->getMock();
        $currencyEntity->expects($this->any())->method('getThreeDigitCode')->willReturn('123');
        $currencyEntity->expects($this->any())->method('getToGBP')->willReturn(1.1);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'rate' => [
                'rateType' => ['one', 'two'],
                'operator' => 'gt',
                'amount' => '1',
                'currency' => $currencyEntity
            ],
            'fields' => ['name', 'rate'],
        ]);

        // check that restricted access doesn't filter by rate or rate type:
        $reportDefinition = new ReportDefinition($form, false);
        $this->assertFalse($reportDefinition->isFilteredBy('Rate'));
        $this->assertFalse($reportDefinition->isFilteredBy('RateType'));
        $this->assertFalse($reportDefinition->isFilteredBy('SOMETHING-ELSE'));

        // check that unrestricted access does filter by rate and rate type:
        $reportDefinition = new ReportDefinition($form, true);
        $this->assertTrue($reportDefinition->isFilteredBy('Rate'));
        $this->assertTrue($reportDefinition->isFilteredBy('RateType'));
        $this->assertFalse($reportDefinition->isFilteredBy('SOMETHING-ELSE'));

        // Check that the currency info gets pulled out
        $this->assertEquals(1.1,  $reportDefinition->getReportCurrencyToGBP());
        $this->assertEquals('123',  $reportDefinition->getReportCurrencyThreeLetterCode());

        // Check that the currency stuff gets translated to a valid SQL fragment
        $this->assertEquals(' * (X.toGBP / 1.1) > 1',  $reportDefinition->getRateLimitAsExpression('X'));

        // check rate types get translated properly
        $this->assertContains($reportDefinition->getRateTypesAsSet(), [
            '(\'two\',\'one\')',
            '(\'one\',\'two\')'
        ]);
    }

    public function testMissingCurrencyInRate()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'rate' => [
                'operator' => 'gt',
                'amount' => '1',
                //'currency' => $currencyEntity
            ],
            'fields' => ['name', 'rate'],
        ]);

        // Check Missing Currency
        $reportDefinition = new ReportDefinition($form, true);
        $this->assertFalse($reportDefinition->isFilteredBy('Rate'));

        // Check we get a default currency
        $this->assertEquals('GBP', $reportDefinition->getReportCurrencyThreeLetterCode());
        $this->assertEquals(1, $reportDefinition->getReportCurrencyToGBP());
    }


    public function testMissingAmountInRate()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'rate' => [
                'operator' => 'gt',
                //'amount' => '1',
                'currency' => $this->getMockBuilder('Fitch\TutorBundle\Entity\Currency')->getMock()
        ],
            'fields' => ['name', 'rate'],
        ]);

        // Check Missing Currency
        $reportDefinition = new ReportDefinition($form, true);
        $this->assertFalse($reportDefinition->isFilteredBy('Rate'));
    }

    public function testMissingOperatorInRate()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'rate' => [
                //'operator' => 'gt',
                'amount' => '1',
                'currency' => $this->getMockBuilder('Fitch\TutorBundle\Entity\Currency')->getMock()
            ],
            'fields' => ['name', 'rate'],
        ]);

        // Check Missing Currency
        $reportDefinition = new ReportDefinition($form, true);
        $this->assertFalse($reportDefinition->isFilteredBy('Rate'));
    }

    public function testReturnedIds()
    {
        $identityEntityOne = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityOne->expects($this->any())->method('getId')->willReturn(1);

        $identityEntityTwo = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityTwo->expects($this->any())->method('getId')->willReturn(2);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'category' => [
                'category' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'language' => [
                'language' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'tutor_type' => [$identityEntityOne, $identityEntityTwo],
            'status' => [$identityEntityOne, $identityEntityTwo],
            'operating_region' => [$identityEntityOne, $identityEntityTwo],
            'competencyLevel' => [$identityEntityOne, $identityEntityTwo],
            'competency' => [
                'competencyType' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'fields' => ['category'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);

        // We can also check that the report isFilteredBy Competency here,
        // and check that the operators made it through
        $this->assertEquals($reportDefinition->getCategoryOperator(), 'or');
        $this->assertEquals($reportDefinition->getLanguageOperator(), 'or');
        $this->assertEquals($reportDefinition->getCompetencyTypeOperator(), 'or');
        $this->assertTrue($reportDefinition->isFilteredBy('Competency'));

        // Each of these should be the same array, containing only the ids 1 and 2
        $returnedIds = [
            'categoryIds'=>$reportDefinition->getCategoryIds(),
            'languageIds'=>$reportDefinition->getLanguageIds(),
            'statusIds'=>$reportDefinition->getStatusIds(),
            'competencyLevelIds'=>$reportDefinition->getCompetencyLevelIds(),
            'competencyTypeIds'=>$reportDefinition->getCompetencyTypeIds(),
            'regionIds'=>$reportDefinition->getRegionIds(),
            'tutorTypeIds'=>$reportDefinition->getTutorTypeIds()
        ];

        // ...but they might come back in any order
        foreach($returnedIds as $setName =>$ids) {
            $this->assertContains(1, $ids, "{$setName} Doesn't contain expected values");
            $this->assertContains(2, $ids, "{$setName} Doesn't contain expected values");
            $this->assertCount(2, $ids, "{$setName} Doesn't contain expected values");
        }

    }

    public function testJustCompetencyTypeIds()
    {
        $identityEntityOne = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityOne->expects($this->any())->method('getId')->willReturn(1);

        $identityEntityTwo = $this->getMockBuilder('Fitch\CommonBundle\Entity\IdentityTraitInterface')->getMock();
        $identityEntityTwo->expects($this->any())->method('getId')->willReturn(2);

        $form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')->getMock();
        $form->expects($this->any())->method('getData')->willReturn([
            'competency' => [
                'competencyType' => [$identityEntityOne, $identityEntityTwo],
                'combine' => 'or'
            ],
            'fields' => ['category'],
        ]);

        $reportDefinition = new ReportDefinition($form, false);

        $this->assertTrue($reportDefinition->isFilteredBy('Competency'));
    }

    public function testStaticMethods()
    {
        // OK - controversy...
        // The static methods are JUST included in the class to simulate constant arrays - though they are mutable
        // so they do a pretty poor job of that.
        //
        // I still want to test that everything in "availableFields" is a "string"=>"string", and that everything in
        // default fields exists as a key in availableFields. Those are useful tests. But they mean testing statics -
        // which is code smell.

        // Probably this logic should be moved to the ACTUAL class, wth an exception thrown if they aren't compliant
        // and the test run as a runtime check on the values. Then the 'default' values can be tested by accessing the
        // accessor method, they could be mutated to be non-compliant, and then the exception checked.
        //
        // But PHP 5.6 has static array constants, and this all goes away. So easier to kick this into the long grass
        // till this project adopts that minimum.

        $availableFields = ReportDefinition::getAvailableFields();
        $defaultFields = ReportDefinition::getDefaultFields();

        foreach($availableFields as $k=>$v) {
            $this->assertInternalType('string', $k);
            $this->assertInternalType('string', $v);
        }

        foreach($defaultFields as $f) {
            $this->assertContains($f, array_keys($availableFields));
        }
    }
}
