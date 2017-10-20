<?php

/*
|--------------------------------------------------------------------------
| Generic messages
|--------------------------------------------------------------------------
|
| The following language lines are for generic messages returned when the
| user initiates particular actions. Substitute ':object' with the name
| of the particular database record
|
*/

return [
    // The following are returned when the user action is successful
    'attached' => ':Object attached.', // Returned when linking two database records together - e.g linking a User to a Team
    'detached' => ':Object detached.', // Returned when unlinking two database records - e.g removing a User from a Team
    'created'  => ':Object created.', // Returned when creating a new database record
    'updated'  => ':Object updated.', // Returned when updating an existing database record
    'deleted'  => ':Object deleted.', // Returned when deleting an existing database record

    'language_updated' => 'Language updated successfully', // Returned when the user successfully switches their language

    'password_reset' => 'Password reset successfully', // Returned when the user successfully resets their password

    'mode_active' => ':mode active', // Returned when switching the application mode. See misc.draft_mode and misc.live_mode
];
