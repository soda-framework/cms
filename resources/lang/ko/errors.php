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
    'error' => '오류',                                                    // Generic error message

    'no_permission' => '이 기능에 액세스 할 수있는 권한이 없습니다.',            // Error message when trying to access a feature that the user does not have permission to access

    'attach'    => '새로운 :object를 붙이는 동안 오류가 발생했습니다',   // Error message when linking two database records together - e.g linking a User to a Team
    'detach'    => '새로운 :object를 분리하는 동안 오류가 발생했습니다', // Error message when unlinking two database records - e.g removing a User from a Team
    'create'    => '새 :object를 만드는 동안 오류가 발생했습니다',     // Error message when creating a new database record
    'not-found' => '지정된 :object를 찾을 수 없습니다',            // Error message when finding an existing database record
    'update'    => ':object를 업데이트하는 중에 오류가 발생했습니다',   // Error message when updating an existing database record
    'delete'    => ':object를 삭제하는 중에 오류가 발생했습니다',      // Error message when deleting an existing database record
];
