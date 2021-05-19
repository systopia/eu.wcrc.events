<?php
/*-------------------------------------------------------+
| ECRC EVENTS IMPLEMENTATION AND MODIFICATIONS           |
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

/**
 * Collection of upgrade steps.
 */
class CRM_Events_Upgrader extends CRM_Events_Upgrader_Base {

    /**
     * Installer: create custom data structures
     */
    public function install()
    {
        $customData = new CRM_Events_CustomData(E::LONG_NAME);

        // option groups
        $customData->syncOptionGroup(E::path('resources/option_group_remote_registration_profiles.json'));
        $customData->syncOptionGroup(E::path('resources/option_group_event_type.json'));

        // custom groups
        $customData->syncCustomGroup(E::path('resources/custom_group_additional_name_information.json'));
        $customData->syncCustomGroup(E::path('resources/custom_group_infos_contact.json'));
        $customData->syncCustomGroup(E::path('resources/custom_group_participant_additional_info.json'));
    }

    /**
     * Setting custom field inactive
     *
     */
    public function upgrade_0001()
    {
        $this->ctx->log->info('Updating data structures');
        $customData = new CRM_Events_CustomData(E::LONG_NAME);
        $customData->syncOptionGroup(E::path('resources/custom_group_infos_contact.json'));
        $customData->syncCustomGroup(E::path('resources/custom_group_participant_additional_info.json'));
        $customData->syncCustomGroup(E::path('resources/custom_group_additional_name_information'));
        return true;
    }
}
