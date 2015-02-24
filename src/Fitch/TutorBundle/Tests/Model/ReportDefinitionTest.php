<?php

namespace Fitch\TutorBundle\Tests\Model;

use Fitch\TutorBundle\Model\ReportDefinition;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RateDefinitionTest extends TestCase
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
                'fields' => [$formField]
            ]);

            $reportDefinition = new ReportDefinition($form, false);
            $this->assertTrue($reportDefinition->isFilteredBy($filterKey));
            $this->assertFalse($reportDefinition->isFilteredBy('SOMETHING FALSE'));
            foreach($filters as $otherFilterKey) {
                if ($otherFilterKey != $filterKey) {
                    $this->assertFalse($reportDefinition->isFilteredBy($otherFilterKey));
                }
            }
        }
   }

   public function testNestedFilters() {
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
               'fields' => [$innerOuter[0]]
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
            'fields' => ['language']
        ]);

        $reportDefinition = new ReportDefinition($form, false);
        // relying on language operator default)
        $this->assertTrue($reportDefinition->isFilteredBy('Language'));
   }

}