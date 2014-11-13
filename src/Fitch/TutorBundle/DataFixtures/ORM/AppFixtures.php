<?php

namespace Fitch\TutorBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

class TestLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/../../Resources/fixtures/005_currency.yml',
            __DIR__ . '/../../Resources/fixtures/010_region.yml',
            __DIR__ . '/../../Resources/fixtures/020_country.yml',
            __DIR__ . '/../../Resources/fixtures/030_competency_level.yml',
            __DIR__ . '/../../Resources/fixtures/040_competency_type.yml',
            __DIR__ . '/../../Resources/fixtures/050_status.yml',
            __DIR__ . '/../../Resources/fixtures/060_tutor_type.yml',
            __DIR__ . '/../../Resources/fixtures/070_note_visibility.yml',

            __DIR__ . '/../../Resources/fixtures/090_filetypes.yml',
            __DIR__ . '/../../Resources/fixtures/450_competency.yml',
            __DIR__ . '/../../Resources/fixtures/460_rate.yml',
            __DIR__ . '/../../Resources/fixtures/470_email.yml',
            __DIR__ . '/../../Resources/fixtures/480_phone.yml',
            __DIR__ . '/../../Resources/fixtures/490_address.yml',
            __DIR__ . '/../../Resources/fixtures/500_tutor.yml',

        );
    }

    public function competencyName()
    {
        $firstWords = [
            'Financial',
            'CFA',
            'Asset',
            'Forex',
            'Lifecycle',
            'Equity',
            'Equity & Option',
            'Derivative',
            'Fixed Income',
            'European',
            'Accounting',
            'Back Office',
            'Mission Critical',
            'Business',
            'Banking',
            'Front Desk'
        ];

        $secondWords = [
            'Trade',
            'Statement Preparation',
            'Statement Analysis',
            'Analysis',
            'Trading',
            'Management',
            'Reporting',
            'Human Resources',
            'Ethics',
            'Auditing',
            'Compliance',
            'Assessment',
        ];

        return $firstWords[array_rand($firstWords)] .' '. $secondWords[array_rand($secondWords)];
    }

}