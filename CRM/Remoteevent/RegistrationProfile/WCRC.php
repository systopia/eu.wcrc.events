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
use Civi\RemoteParticipant\Event\ValidateEvent as ValidateEvent;
use Civi\RemoteParticipant\Event\GetCreateParticipantFormEvent as GetCreateParticipantFormEvent;
use Civi\RemoteParticipant\Event\RegistrationEvent as RegistrationEvent;

/**
 * Implements default WCRC profile
 */
class CRM_Remoteevent_RegistrationProfile_WCRC extends CRM_Remoteevent_RegistrationProfile
{
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
                'required'    => 1,
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
                'parent'      => 'contact_address'
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
                'parent'      => 'contact_comm'
            ],

            #Language Information
            'language' => [
                'type'        => 'fieldset',
                'name'        => 'language',
                'label'       => $l10n->localise("Language Information"),
                'weight'      => 30,
                'description' => '',
            ],
            'mother_language' => [
                'name'        => 'mother_language',
                'type'        => 'Text', #wird evtl noch geändert
                'validation'  => '',
                'weight'      => 150,
                'required'    => 0,
                'label'       => $l10n->localise('Mother Language'),
                'description' => $l10n->localise("Your Mother Tongue"),
                'parent'      => 'language',
            ],
            'preferred_language'    => [
                'name'        => 'preferred_language',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 160,
                'required'    => 1,
                'options'     => $this->getOptions('languages', $locale),
                'label'       => $l10n->localise('Preferred Language'),
                'parent'      => 'language',
            ],
            'spoken_language' => [
                'type'        => 'fieldset',
                'name'        => 'spoken_language',
                'label'       => $l10n->localise("Languages Spoken"),
                'weight'      => 180,
                'description' => '',
            ],
            'english' => [
                'name'        => 'english',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 200,
                'required'    => 0,
                'label'       => $l10n->localise('English'),
                'parent'      => 'spoken_language',
            ],
            'german' => [
                'name'        => 'german',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 210,
                'required'    => 0,
                'label'       => $l10n->localise('German'),
                'parent'      => 'spoken_language',
            ],
            'french' => [
                'name'        => 'french',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 220,
                'required'    => 0,
                'label'       => $l10n->localise('French'),
                'parent'      => 'spoken_language',
            ],
            'spanish' => [
                'name'        => 'spanish',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 230,
                'required'    => 0,
                'label'       => $l10n->localise('Spanish'),
                'parent'      => 'spoken_language',
            ],
            'indonesian' => [
                'name'        => 'indonesian',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 240,
                'required'    => 0,
                'label'       => $l10n->localise('Indonesian'),
                'parent'      => 'spoken_language',
            ],
            'korean' => [
                'name'        => 'korean',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 240,
                'required'    => 0,
                'label'       => $l10n->localise('Korean'),
                'parent'      => 'spoken_language',
            ],

            #Transportation Information
            'transportation_info' => [
                'type'        => 'fieldset',
                'name'        => 'transportation_info',
                'label'       => $l10n->localise("Transportation Information"),
                'weight'      => 50,
            ],
            'arrival_time'    => [
                'name'        => 'arrival_time',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 230,
                'required'    => 1,
                'label'       => $l10n->localise('Arrival time'),
                'parent'      => 'transportation_info'
            ],
            'departure_time'    => [
                'name'        => 'departure_time',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 240,
                'required'    => 1,
                'label'       => $l10n->localise('Departure time'),
                'parent'      => 'transportation_info'
            ],
            'departure_time_home_country'    => [
                'name'        => 'departure_time_home_country',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 250,
                'required'    => 1,
                'label'       => $l10n->localise('Departure time home country'),
                'description' => $l10n->localise("Needed for visa purposes"),
                'parent'      => 'transportation_info'
            ],
            'arrival_type' => [
                'name'        => 'arrival_type',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 260,
                'required'    => 0,
                'label'       => $l10n->localise('Arrival Type'),
                'parent'      => 'transportation_info'
            ],
            'departure_type' => [
                'name'        => 'departure_type',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 270,
                'required'    => 0,
                'label'       => $l10n->localise('Departure Type'),
                'parent'      => 'transportation_info'
            ],


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
                'label'       => $l10n->localise('Dietary Restrictions'),
                'parent'      => 'additional_information'
            ],
            'emergency_contact' => [
                'name'        => 'emergency_contact',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 290,
                'required'    => 0,
                'label'       => $l10n->localise('Emergency Contact'),
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
     * @param GetCreateParticipantFormEvent $resultsEvent
     *   the locale to use, defaults to null none. Use 'default' for current
     *
     */
    public function addDefaultValues(GetCreateParticipantFormEvent $resultsEvent)
    {
        if ($resultsEvent->getContactID()) {
            // get contact field list from that
            $data_mapping = [
                'first_name_official'         => 'additional_name_information.first_name_official',
                'middle_name_official'        => 'additional_name_information.middle_name_official',
                'last_name_official'          => 'additional_name_information.last_name_official',
                'preferred_name'              => 'additional_name_information.preferred_name',
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
                'mother_language'             => 'infos_contact.mother_language',
                'preferred_language'          => 'preferred_language',
                'english'                     => 'infos_contact.english',
                'german'                      => 'infos_contact.german',
                'french'                      => 'infos_contact.french',
                'spanish'                     => 'infos_contact.spanish',
                'indonesian'                  => 'infos_contact.indonesian',
                'korean'                      => 'infos_contact.korean',
            ];
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
        $mapping = [
            'first_name_official'         => 'additional_name_information.first_name_official',
            'middle_name_official'        => 'additional_name_information.middle_name_official',
            'last_name_official'          => 'additional_name_information.last_name_official',
            'preferred_name'              => 'additional_name_information.preferred_name',
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
            'mother_language'             => 'infos_contact.mother_language',
            'preferred_language'          => 'preferred_language',
            'english'                     => 'infos_contact.english',
            'german'                      => 'infos_contact.german',
            'french'                      => 'infos_contact.french',
            'spanish'                     => 'infos_contact.spanish',
            'indonesian'                  => 'infos_contact.indonesian',
            'korean'                      => 'infos_contact.korean',
        ];
        foreach ($mapping as $registration_field => $contact_custom_field) {
            if (isset($submission[$registration_field])) {
                $contact_data[$contact_custom_field] = $submission[$registration_field];
            }
        }

        // todo: derive CiviCRM name fields (first_name, last_name, middle_name from the ones above)
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
        $mapping = [
            'arrival_time'                => 'participant_additional_info.arrival_time',
            'departure_time'              => 'participant_additional_info.departure_time',
            'departure_time_home_country' => 'participant_additional_info.departure_time_home_country',
            'arrival_type'                => 'participant_additional_info.arrival_type',
            'departure_type'              => 'participant_additional_info.departure_type',
            'dietary_restrictions'        => 'participant_additional_info.dietary_restrictions',
            'emergency_contact'           => 'participant_additional_info.emergency_contact',
            'member_church_name'          => 'participant_additional_info.member_church_name',
            'member_church_address'       => 'participant_additional_info.member_church_address',
        ];
        $participant_data = &$registration->getParticipant();
        foreach ($mapping as $registration_key => $custom_field) {
            $participant_data[$custom_field] = CRM_Utils_Array::value($registration_key, $participant_data, '');
        }

        // todo: anything else?

    }
}
