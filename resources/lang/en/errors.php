<?php

/*
|--------------------------------------------------------------------------
| Error messages
|--------------------------------------------------------------------------
|
| The following language lines are for generic error messages/phrases,
| substituting ':object' with the name of a particular database record
|
*/

return [
    'error' => 'Error', // Generic error message

    'no_permission' => 'You do not have permission to access this feature.', // Error message when trying to access a feature that the user does not have permission to access

    'attach'    => 'An error occured whilst attaching new :object', // Error message when linking two database records together - e.g linking a User to a Team
    'detach'    => 'An error occured whilst detaching new :object', // Error message when unlinking two database records - e.g removing a User from a Team
    'create'    => 'An error occured whilst creating new :object',  // Error message when creating a new database record
    'not-found' => 'The specified :object could not be found',      // Error message when finding an existing database record
    'update'    => 'An error occured whilst updating your :object', // Error message when updating an existing database record
    'delete'    => 'An error occured whilst deleting your :object', // Error message when deleting an existing database record
];
