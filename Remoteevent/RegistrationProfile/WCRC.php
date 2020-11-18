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
use \Civi\RemoteParticipant\Event\ValidateEvent as ValidateEvent;
use Civi\RemoteParticipant\Event\GetCreateParticipantFormEvent as GetCreateParticipantFormEvent;

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
        return 'WCRC Default';
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
            'first_name_official'   => [
                'name'        => 'first_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 10,
                'required'    => 1,
                'label'       => $l10n->localise('First name official'),
                'description' => $l10n->localise("First name (according to official document)"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'middle_name_official'   => [
                'name'        => 'middle_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 20,
                'required'    => 0,
                'label'       => $l10n->localise('Middle name official'),
                'description' => $l10n->localise("Middle name (according to official document)"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'last_name_official'    => [
                'name'        => 'last_name_official',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 30,
                'required'    => 1,
                'label'       => $l10n->localise('Last name official'),
                'description' => $l10n->localise("Last name (according to official document"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'preferred_name'    => [
                'name'        => 'preferred_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 40,
                'required'    => 1,
                'label'       => $l10n->localise('Last name official'),
                'description' => $l10n->localise("Last name (according to official document"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'formal_title'    => [
                'name'        => 'formal_title',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 50,
                'required'    => 0,
                'label'       => $l10n->localise('Formal title'),
                'description' => $l10n->localise("Title"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'prefix_id'    => [
                'name'        => 'prefix_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 60,
                'required'    => 1,
                'options'     => $this->getOptions('prefix_id', $locale),
                'label'       => $l10n->localise('Prefix'),
                'description' => $l10n->localise("Prefix"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'birth_date'    => [
                'name'        => 'birth_date',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Birth Date'),
                'description' => $l10n->localise("Birth Date"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'street_address'    => [
                'name'        => 'street_address',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 80,
                'required'    => 1,
                'label'       => $l10n->localise('street_address'),
                'description' => $l10n->localise("Street Address and Number"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'city'    => [
                'name'        => 'city',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('city'),
                'description' => $l10n->localise("City"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'postal_code'    => [
                'name'        => 'postal_code',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 100,
                'required'    => 1,
                'label'       => $l10n->localise('postal_code'),
                'description' => $l10n->localise("Postal code"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'state_province_id'    => [
                'name'        => 'state_province_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 110,
                'required'    => 1,
                'options'     => $this->getOptions('state_province_id', $locale),
                'label'       => $l10n->localise('Prefix'),
                'description' => $l10n->localise("Prefix"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'country_id'    => [
                'name'        => 'country_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 120,
                'required'    => 1,
                'options'     => $this->getOptions('country_id', $locale),
                'label'       => $l10n->localise('Country'),
                'description' => $l10n->localise("Country"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'email' => [
                'name'        => 'email',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 130,
                'required'    => 1,
                'label'       => $l10n->localise('E-Mail'),
                'description' => $l10n->localise("E-Mail Address"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],
            'phone' => [
                'name'        => 'phone',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 140,
                'required'    => 0,
                'label'       => $l10n->localise('Phone'),
                'description' => $l10n->localise("Phone"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Information"),
            ],

            #Language Information

            'mother_language' => [
                'name'        => 'mother_language',
                'type'        => 'Text', #wird evtl noch geändert
                'validation'  => '',
                'weight'      => 150,
                'required'    => 0,
                'label'       => $l10n->localise('Phone'),
                'description' => $l10n->localise("Phone"),
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'preferred_language'    => [
                'name'        => 'preferred_language',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 160,
                'required'    => 1,
                'options'     => $this->getOptions('preferred_language', $locale),
                'label'       => $l10n->localise('Preferred Language'),
                'description' => $l10n->localise("Preferred Language"),
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],

            'english' => [
                'name'        => 'english',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 170,
                'required'    => 1,
                'options'     => $this->getOptions('english', $locale),
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'german' => [
                'name'        => 'german',
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'french' => [
                'name'        => 'french',
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'spanish' => [
                'name'        => 'spanish',
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'indonesian' => [
                'name'        => 'indonesian',
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],
            'korean' => [
                'name'        => 'korean',
                'group_name'  => 'language',
                'group_label' => $l10n->localise("Language Information"),
            ],

            #Transportation Information
            'arrival_time'    => [
                'name'        => 'arrival_time',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 230,
                'required'    => 1,
                'label'       => $l10n->localise('Arrival time'),
                'description' => $l10n->localise("Arrival time"),
                'group_name'  => 'transportation_info',
                'group_label' => $l10n->localise("Transportation Information"),
            ],
            'departure_time'    => [
                'name'        => 'departure_time',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 240,
                'required'    => 1,
                'label'       => $l10n->localise('Departure time'),
                'description' => $l10n->localise("Departure time"),
                'group_name'  => 'transportation_info',
                'group_label' => $l10n->localise("Transportation Information"),
            ],
            'departure_time_home_country'    => [
                'name'        => 'departure_time_home_country',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 250,
                'required'    => 1,
                'label'       => $l10n->localise('Departure time home country'),
                'description' => $l10n->localise("Departure time from home country (for visa purposes)"),
                'group_name'  => 'transportation_info',
                'group_label' => $l10n->localise("Transportation Information"),
            ],
            'arrival_type' => [
                'name'        => 'arrival_type',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 260,
                'required'    => 0,
                'label'       => $l10n->localise('Arrival Type'),
                'description' => $l10n->localise("Arrival Type"),
                'group_name'  => 'transportation_info',
                'group_label' => $l10n->localise("Transportation Information"),
            ],
            'departure_type' => [
                'name'        => 'departure_type',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 270,
                'required'    => 0,
                'label'       => $l10n->localise('Departure Type'),
                'description' => $l10n->localise("Departure Type"),
                'group_name'  => 'transportation_info',
                'group_label' => $l10n->localise("Transportation Information"),
            ],
            'dietary_restrictions' => [
                'name'        => 'dietary_restrictions',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 280,
                'required'    => 0,
                'label'       => $l10n->localise('Dietary Restrictions'),
                'description' => $l10n->localise("Departure Type"),
                'group_name'  => 'additional_info',
                'group_label' => $l10n->localise("Additional Information"),
            ],
            'emergency_contact' => [
                'name'        => 'emergency_contact',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 290,
                'required'    => 0,
                'label'       => $l10n->localise('Emergency Contact'),
                'description' => $l10n->localise("Emergency Contact"),
                'group_name'  => 'additional_info',
                'group_label' => $l10n->localise("Additional Information"),
            ],
            'member_church_name' => [
                'name'        => 'member_church_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 300,
                'required'    => 0,
                'label'       => $l10n->localise('Member church name'),
                'description' => $l10n->localise("Member church name"),
                'group_name'  => 'additional_info',
                'group_label' => $l10n->localise("Additional Information"),
            ],
            'member_church_address' => [
                'name'        => 'member_church_address',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 310,
                'required'    => 0,
                'label'       => $l10n->localise('Member church address'),
                'description' => $l10n->localise("Member church address"),
                'group_name'  => 'additional_info',
                'group_label' => $l10n->localise("Additional Information"),
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
                'first_name_official'   => 'additional_name_information.first_name_official',
                'middle_name_official'  => 'additional_name_information.middle_name_official',
                'last_name_official'    => 'additional_name_information.last_name_official',
                'preferred_name'        => 'additional_name_information.preferred_name',
                'formal_title'          => 'formal_title',
                'prefix_id'             => 'prefix_id',
                'birth_date'            => 'birth_date',
                'street_address'        => 'street_address',
                'city'                  => 'city',
                'postal_code'           => 'postal_code',
                'state_province_id'     => 'state_province_id',
                'country_id'            => 'country_id',
                'email'                 => 'email',
                'phone'                 => 'phone',
                'mother_language'       => 'infos_contact.mother_language',
                'preferred_language'    => 'infos_contact.preferred_language',
                'english'               => 'infos_contact.english',
                'german'                => 'infos_contact.german',
                'french'                => 'infos_contact.french',
                'spanish'               => 'infos_contact.spanish',
                'indonesian'            => 'infos_contact.indonesian',
                'korean'                => 'infos_contact.korean',
                'arrival_time'          => 'participant_additional_info.arrival_time',
                'departure_time'        => 'participant_additional_info.departure_time',
                'departure_time_home_country'=> 'participant_additional_info.departure_time_home_country',
                'arrival_type'          => 'participant_additional_info.arrival_type',
                'departure_type'        => 'participant_additional_info.departure_type',
                'dietary_restrictions'  => 'participant_additional_info.dietary_restrictions',
                'emergency_contact'     => 'participant_additional_info.emergency_contact',
                'member_church_name'    => 'participant_additional_info.member_church_name',
                'member_church_address' => 'participant_additional_info.member_church_address',
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

        // validate that the parish matches the district
        $submission = $validationEvent->getSubmission();
        if (!empty($submission['church_parish']) && !empty($submission['church_district'])) {
            // the first 6 digits of the parish should match the district
            if ($submission['church_district'] != substr($submission['church_parish'], 0, 6)) {
                $l10n = $validationEvent->getLocalisation();
                $validationEvent->addError('church_parish', $l10n->localise("Diese Kirchengemeinde gehört nicht zum gewählten Kirchenkreis."));
            }
        }
    }

}
