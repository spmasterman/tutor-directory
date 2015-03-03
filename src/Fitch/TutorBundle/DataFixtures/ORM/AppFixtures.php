<?php

namespace Fitch\TutorBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

/**
 * Class AppFixtures.
 *
 * @codeCoverageIgnore
 *
 * This does not independent testing - as it runs as part of the setup of FixturesWebTestCase - they will all fail
 * if this fails.
 */
class AppFixtures extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        // These are the lookup tables etc that should be deployed
        $productionFixtures = [
            __DIR__.'/../../Resources/fixtures/005_currency.yml',
            __DIR__.'/../../Resources/fixtures/010_region.yml',
            __DIR__.'/../../Resources/fixtures/015_user.yml',
            __DIR__.'/../../Resources/fixtures/020_country.yml',
            __DIR__.'/../../Resources/fixtures/030_competency_level.yml',
            __DIR__.'/../../Resources/fixtures/050_status.yml',
            __DIR__.'/../../Resources/fixtures/060_tutor_type.yml',
            __DIR__.'/../../Resources/fixtures/070_business_area.yml',
            __DIR__.'/../../Resources/fixtures/080_proficiency.yml',
            __DIR__.'/../../Resources/fixtures/090_filetypes.yml',
            __DIR__.'/../../Resources/fixtures/100_languages.yml',
            __DIR__.'/../../Resources/fixtures/110_category.yml',
            __DIR__.'/../../Resources/fixtures/120_competency_type.yml',
            __DIR__.'/../../Resources/fixtures/130_reports.yml',
        ];

        // This is test data (generated via Faker etc)
        $developmentFixtures = array_merge($productionFixtures, [
            __DIR__.'/../../Resources/fixtures/450_competency.yml',
            __DIR__.'/../../Resources/fixtures/460_rate.yml',
            __DIR__.'/../../Resources/fixtures/470_email.yml',
            __DIR__.'/../../Resources/fixtures/480_phone.yml',
            __DIR__.'/../../Resources/fixtures/490_address.yml',
            __DIR__.'/../../Resources/fixtures/495_note.yml',
            __DIR__.'/../../Resources/fixtures/500_tutor.yml',
            __DIR__.'/../../Resources/fixtures/510_tutor_languages.yml',
        ]);

        $testFixtures = array_map(function ($fileName) {
            return str_replace('/fixtures/', '/fixtures/test/', $fileName);
        }, $developmentFixtures);

        $environment = $this->container->get('kernel')->getEnvironment();

        switch ($environment) {
            case 'prod':
                return $productionFixtures;
            case 'test':
                return $testFixtures;
            default:
                return $developmentFixtures; // demo, dev etc
        }
    }
}
