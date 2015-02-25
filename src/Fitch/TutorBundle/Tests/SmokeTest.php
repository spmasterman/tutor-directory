<?php

namespace Fitch\TutorBundle\Tests\Controller;

use Fitch\CommonBundle\Tests\AuthorisedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    use AuthorisedClientTrait;


    public function testALotOfPagesAsSuperAdmin()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('xsuper');

        $routes = [
            'business_area          ' => ['GET', '/admin/business_area/', []],
            'business_area_new      ' => ['GET', '/admin/business_area/new', []],
            'business_area_show     ' => ['GET', '/admin/business_area/1', []],
            'business_area_edit     ' => ['GET', '/admin/business_area/1/edit', []],
            'category               ' => ['GET', '/admin/category/', []],
            'category_new           ' => ['GET', '/admin/category/new', []],
            'category_show          ' => ['GET', '/admin/category/1', []],
            'category_edit          ' => ['GET', '/admin/category/1/edit', []],
            'competency_level       ' => ['GET', '/admin/level/competency/', []],
            'competency_level_new   ' => ['GET', '/admin/level/competency/new', []],
            'competency_level_show  ' => ['GET', '/admin/level/competency/1', []],
            'competency_level_edit  ' => ['GET', '/admin/level/competency/1/edit', []],
            'competency_type        ' => ['GET', '/admin/type/competency/', []],
            'competency_type_new    ' => ['GET', '/admin/type/competency/new', []],
            'competency_type_show   ' => ['GET', '/admin/type/competency/1', []],
            'competency_type_edit   ' => ['GET', '/admin/type/competency/1/edit', []],
            'country                ' => ['GET', '/admin/country/', []],
            'country_new            ' => ['GET', '/admin/country/new', []],
            'country_show           ' => ['GET', '/admin/country/1', []],
            'country_edit           ' => ['GET', '/admin/country/1/edit', []],
            'currency               ' => ['GET', '/admin/currency/', []],
            'currency_new           ' => ['GET', '/admin/currency/new', []],
            'currency_show          ' => ['GET', '/admin/currency/1', []],
            'currency_edit          ' => ['GET', '/admin/currency/1/edit', []],
            'all_currencies         ' => ['GET', '/admin/currency/active', []],
            'file_type              ' => ['GET', '/admin/type/file/', []],
            'file_type_new          ' => ['GET', '/admin/type/file/new', []],
            'file_type_show         ' => ['GET', '/admin/type/file/1', []],
            'file_type_edit         ' => ['GET', '/admin/type/file/1/edit', []],
            'language               ' => ['GET', '/admin/language/', []],
            'language_new           ' => ['GET', '/admin/language/new', []],
            'language_show          ' => ['GET', '/admin/language/1', []],
            'language_edit          ' => ['GET', '/admin/language/1/edit', []],
            'region                 ' => ['GET', '/admin/region/', []],
            'region_new             ' => ['GET', '/admin/region/new', []],
            'region_show            ' => ['GET', '/admin/region/1', []],
            'region_edit            ' => ['GET', '/admin/region/1/edit', []],
            'proficiency            ' => ['GET', '/admin/proficiency/', []],
            'proficiency_new        ' => ['GET', '/admin/proficiency/new', []],
            'proficiency_show       ' => ['GET', '/admin/proficiency/1', []],
            'proficiency_edit       ' => ['GET', '/admin/proficiency/1/edit', []],
            'all_proficiencies      ' => ['GET', '/admin/proficiency/all', []],
            'tutor_profile(profile) ' => ['GET', '/profile/1/profile', []],
            'tutor_profile(engagement)' => ['GET', '/profile/1/engagement', []],
            'tutor_profile(competency)' => ['GET', '/profile/1/competency', []],
            'tutor_profile(files)   ' => ['GET', '/profile/1/files', []],
            'active_languages       ' => ['GET', '/profile/active/language', []],
            'active_proficiencies   ' => ['GET', '/profile/active/proficiency', []],
            'active_competency_type ' => ['GET', '/profile/active/competency/type', []],
            'active_competency_level' => ['GET', '/profile/active/competency/level', []],
            'all_regions            ' => ['GET', '/profile/active/region', []],
            'all_tutor_types        ' => ['GET', '/profile/active/tutor_type', []],
            'all_status             ' => ['GET', '/profile/active/status', []],
            'all_file_types         ' => ['GET', '/profile/active/file_type', []],
            'profile_dynamic_data   ' => ['GET', '/profile/prototype/1', []],
            'report_list            ' => ['GET', '/reportlist', []],
            'report_header          ' => ['GET', '/report', []],
            'report_show            ' => ['GET', '/report/show/1', []],
            'report_edit            ' => ['GET', '/report/1/edit', []],
            'status                 ' => ['GET', '/admin/status/', []],
            'status_new             ' => ['GET', '/admin/status/new', []],
            'status_show            ' => ['GET', '/admin/status/1', []],
            'status_edit            ' => ['GET', '/admin/status/1/edit', []],
            'home                   ' => ['GET', '/', []],
//            'all_tutors             ' => ['GET', '/results', []],
            'tutor_new              ' => ['GET', '/new', []],
            'tutor_type             ' => ['GET', '/admin/type/tutor/', []],
            'tutor_type_new         ' => ['GET', '/admin/type/tutor/new', []],
            'tutor_type_show        ' => ['GET', '/admin/type/tutor/1', []],
            'tutor_type_edit        ' => ['GET', '/admin/type/tutor/1/edit', []],
            'terms                  ' => ['GET', '/terms', []],
            'user_new               ' => ['GET', '/user/new', []],
            'user_show              ' => ['GET', '/user/1', []],
            'user_edit              ' => ['GET', '/user/1/edit', []],
        ];

        foreach ($routes as $name => $routeBits) {
            $client->request($routeBits[0], $routeBits[1], $routeBits[2]);
            $this->assertEquals(200,
                $client->getResponse()->getStatusCode(),
                "Unexpected HTTP status code for {$name} :" . print_r($routeBits)
            );
        }
    }
}
