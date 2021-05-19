<?php
/*-------------------------------------------------------+
| SYSTOPIA Remote Event Extension                        |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Events_ExtensionUtil as E;
use Civi\RemoteParticipant\Event\ChangingEvent as ChangingEvent;
use Civi\RemoteParticipant\Event\RegistrationEvent as RegistrationEvent;
use Civi\RemoteParticipant\Event\UpdateEvent as UpdateEvent;
use Civi\RemoteParticipant\Event\ValidateEvent as ValidateEvent;
use Civi\RemoteParticipant\Event\GetParticipantFormEventBase as GetParticipantFormEventBase;

/**
 * Implements default WCRC profile
 */
class CRM_Remoteevent_RegistrationProfile_WCRC extends CRM_Remoteevent_RegistrationProfile
{
    /** @var array maps the form fields to contact fields */
    const CONTACT_MAPPING = [
        'first_name_official'         => 'additional_name_information.first_name_official',
        'middle_name_official'        => 'additional_name_information.middle_name_official',
        'last_name_official'          => 'additional_name_information.last_name_official',
        'preferred_name'              => 'additional_name_information.preferred_name',
        'passport_information'        => 'additional_name_information.passport_information',
        'formal_title'                => 'formal_title',
        'prefix_id'                   => 'prefix_id',
        'birth_date'                  => 'birth_date',
        'street_address'              => 'street_address',
        'city'                        => 'city',
        'postal_code'                 => 'postal_code',
        'state_province_id'           => 'state_province_id',
        'country_id'                  => 'country_id',
        'email'                       => 'email',
        'phone'                       => 'phone',
        'preferred_language'          => 'preferred_language',
    ];

    /** @var array extra mapping to map the names not only to the official fields */
    const CONTACT_NAME_MAPPING = [
        'first_name_official'         => 'first_name',
        'middle_name_official'        => 'middle_name',
        'last_name_official'          => 'last_name',
    ];

    /** @var array maps the form fields to participant fields */
    const PARTICIPANT_MAPPING = [
        'departure_day_home_country'  => 'participant_additional_info.departure_day_home_country',
        'dietary_restrictions'        => 'participant_additional_info.dietary_restrictions',
        'emergency_contact'           => 'participant_additional_info.emergency_contact',
        'member_church_name'          => 'participant_additional_info.member_church_name',
        'member_church_address'       => 'participant_additional_info.member_church_address',
        'other_language'              => 'participant_additional_info.other_language',
        'frequent_flyer_info'         => 'participant_additional_info.frequent_flyer_info',
    ];

    /**
     * Get the internal name of the profile represented
     *
     * @return string name
     */
    public function getName()
    {
        return 'WCRC';
    }

    /**
     * @param string $locale
     *   the locale to use, defaults to null (current locale)
     *
     * @return array field specs
     * @see CRM_Remoteevent_RegistrationProfile::getFields()
     *
     */
    public function getFields($locale = null)
    {
        $l10n = CRM_Remoteevent_Localisation::getLocalisation($locale);
        $fields = [
            'contact_base' => [
                'type'        => 'fieldset',
                'name'        => 'contact_base',
                'label'       => $l10n->localise("Contact Information"),
                'weight'      => 10,
                'description' => '',
            ],
            'first_name_official'   => [
                'name'        => 'first_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 10,
                'required'    => 1,
                'label'       => $l10n->localise('First name (official)'),
                'description' => $l10n->localise("First name according to official document"),
                'parent'      => 'contact_base'
            ],
            'middle_name_official'   => [
                'name'        => 'middle_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 20,
                'required'    => 0,
                'label'       => $l10n->localise('Middle name (official)'),
                'description' => $l10n->localise("Middle name according to official document"),
                'parent'      => 'contact_base'
            ],
            'last_name_official'    => [
                'name'        => 'last_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 30,
                'required'    => 1,
                'label'       => $l10n->localise('Last name (official)'),
                'description' => $l10n->localise("Last name according to official document"),
                'parent'      => 'contact_base'
            ],
            'preferred_name'    => [
                'name'        => 'preferred_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 40,
                'required'    => 0,
                'label'       => $l10n->localise('Preferred First Name'),
                'description' => $l10n->localise("Which first name do you prefer?"),
                'parent'      => 'contact_base'
            ],
            'formal_title'    => [
                'name'        => 'formal_title',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 50,
                'required'    => 0,
                'label'       => $l10n->localise('Formal title'),
                'parent'      => 'contact_base'
            ],
            'prefix_id'    => [
                'name'        => 'prefix_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 60,
                'required'    => 1,
                'options'     => $this->getOptions('individual_prefix', $locale),
                'label'       => $l10n->localise('Prefix'),
                'parent'      => 'contact_base'
            ],
            'birth_date'    => [
                'name'        => 'birth_date',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Birth Date'),
                'parent'      => 'contact_base'
            ],

            'contact_address' => [
                'type'        => 'fieldset',
                'name'        => 'contact_address',
                'label'       => $l10n->localise("Contact Address"),
                'weight'      => 20,
                'description' => '',
            ],
            'street_address'    => [
                'name'        => 'street_address',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 80,
                'required'    => 1,
                'label'       => $l10n->localise('Street Address and Number'),
                'parent'      => 'contact_address'
            ],
            'city'    => [
                'name'        => 'city',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('City'),
                'parent'      => 'contact_address'
            ],
            'postal_code'    => [
                'name'        => 'postal_code',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 100,
                'required'    => 1,
                'label'       => $l10n->localise('Postal Code'),
                'parent'      => 'contact_address'
            ],
            'country_id'    => [
                'name'        => 'country_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 110,
                'required'    => 1,
                'options'     => $this->getCountries($locale),
                'label'       => $l10n->localise('Country'),
                'parent'      => 'contact_address',
                'dependencies'=> [
                    [
                        'dependent_field'       => 'state_province_id',
                        'hide_unrestricted'     => 1,
                        'hide_restricted_empty' => 1,
                        'command'               => 'restrict',
                        'regex_subject'         => 'dependent',
                        'regex'                 => '^({current_value}-[0-9]+)$',
                    ],
                ],
            ],
            'state_province_id'    => [
                'name'        => 'state_province_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 120,
                'required'    => 1,
                'options'     => $this->getStateProvinces($locale),
                'label'       => $l10n->localise('State or Province'),
                'parent'      => 'contact_address'
            ],

            'contact_comm' => [
                'type'        => 'fieldset',
                'name'        => 'contact_comm',
                'label'       => $l10n->localise("Contact Information"),
                'weight'      => 30,
                'description' => '',
            ],
            'email' => [
                'name'        => 'email',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 130,
                'required'    => 1,
                'label'       => $l10n->localise('E-Mail Address'),
                'parent'      => 'contact_comm'
            ],
            'phone' => [
                'name'        => 'phone',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 140,
                'required'    => 0,
                'label'       => $l10n->localise('Phone'),
                'description' => $l10n->localise('Please include country code'),
                'parent'      => 'contact_comm',
            ],

            #Language Information
            'language' => [
                'type'        => 'fieldset',
                'name'        => 'language',
                'label'       => $l10n->localise("Language Information"),
                'weight'      => 30,
                'description' => '',
            ],
            'preferred_language'    => [
                'name'        => 'preferred_language',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 160,
                'required'    => 1,
                'options'     => $this->getOptions('languages', $locale, [], true),
                'label'       => $l10n->localise('Preferred Language'),
                'parent'      => 'language',
            ],
            'other_language' => [
                'name'        => 'other_language',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 170,
                'required'    => 0,
                'label'       => $l10n->localise('Other languages spoken'),
                'parent'      => 'language'
            ],

            #Travel Information
            'transportation_info' => [
                'type'        => 'fieldset',
                'name'        => 'transportation_info',
                'label'       => $l10n->localise("Travel Information"),
                'weight'      => 50,
            ],
            'departure_day_home_country'    => [
                'name'        => 'departure_day_home_country',
                'type'        => 'Datetime',
                'validation'  => 'Timestamp',
                'weight'      => 250,
                'required'    => 1,
                'label'       => $l10n->localise('Preferred departure day from home country'),
                'parent'      => 'transportation_info'
            ],
            'frequent_flyer_info' => [
                'name'        => 'frequent_flyer_info',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 260,
                'required'    => 0,
                'label'       => $l10n->localise('Frequent flyer information'),
                'parent'      => 'transportation_info'
            ],

            #Additional Information
            'additional_information' => [
                'type'        => 'fieldset',
                'name'        => 'additional_information',
                'label'       => $l10n->localise("Additional Information"),
                'weight'      => 50,
            ],
            'dietary_restrictions' => [
                'name'        => 'dietary_restrictions',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 280,
                'required'    => 0,
                'label'       => $l10n->localise('Dietary restrictions / mobility restrictions'),
                'parent'      => 'additional_information'
            ],
            'emergency_contact' => [
                'name'        => 'emergency_contact',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 290,
                'required'    => 0,
                'label'       => $l10n->localise('Emergency contact'),
                'parent'      => 'additional_information'
            ],
            'member_church_name' => [
                'name'        => 'member_church_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 300,
                'required'    => 0,
                'label'       => $l10n->localise('Member church name'),
                'parent'      => 'additional_information'
            ],
            'member_church_address' => [
                'name'        => 'member_church_address',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 310,
                'required'    => 0,
                'label'       => $l10n->localise('Member church address'),
                'parent'      => 'additional_information'
            ],
        ];

        return $fields;
    }

    /**
     * Add the default values to the form data, so people using this profile
     *  don't have to enter everything themselves
     *
     * @param GetParticipantFormEventBase $resultsEvent
     *   the locale to use, defaults to null none. Use 'default' for current
     *
     */
    public function addDefaultValues(GetParticipantFormEventBase $resultsEvent)
    {
        if ($resultsEvent->getContactID()) {
            // get contact field list from that
            $data_mapping = self::CONTACT_MAPPING;
            $field_list = array_flip($data_mapping);
            CRM_Events_CustomData::resolveCustomFields($field_list);

            // adn then use the generic function
            $this->addDefaultContactValues($resultsEvent, array_keys($field_list), $field_list);
        }
    }

    /**
     * Validate the profile fields individually.
     * This only validates the mere data types,
     *   more complex validation (e.g. over multiple fields)
     *   have to be performed by the profile implementations
     *
     * @param ValidateEvent $validationEvent
     *      event triggered by the RemoteParticipant.validate or submit API call
     */
    public function validateSubmission($validationEvent)
    {
        parent::validateSubmission($validationEvent);

        // todo: custom validation in here
    }

    /**
     * Allows us to apply the data for the contact just before it's being created
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function applySubmissionToContact($registration)
    {
        // map registration fields to custom fields
        $contact_data = &$registration->getContactData();
        $submission = $registration->getSubmission();
        foreach (self::CONTACT_MAPPING as $registration_field => $contact_custom_field) {
            if (isset($submission[$registration_field])) {
                $contact_data[$contact_custom_field] = $submission[$registration_field];
            }
        }

        // add CiviCRM default names
        foreach (self::CONTACT_NAME_MAPPING as $registration_field => $contact_field) {
            if (!empty($submission[$registration_field])) {
                $contact_data[$contact_field] = $submission[$registration_field];
            }
        }

        // format the state_province_id
        if (!empty($contact_data['state_province_id'])) {
            if (preg_match('/^[0-9]+-([0-9]+)$/', $contact_data['state_province_id'], $match)) {
                $contact_data['state_province_id'] = $match[1];
            }
        }

        // todo: format/transform data?
        // todo: anything else?
    }


    /**
     * Allows us to apply the data for the participant just before it's being created
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function applySubmissionToParticipant($registration)
    {
        // map registration fields to custom fields
        $mapping = self::PARTICIPANT_MAPPING;
        $participant_data = &$registration->getParticipantData();
        foreach ($mapping as $registration_key => $custom_field) {
            $participant_data[$custom_field] = CRM_Utils_Array::value($registration_key, $participant_data, '');
        }

        // todo: anything else?

    }
}
