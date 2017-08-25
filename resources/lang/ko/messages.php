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
    'attached' => '첨부 된 :Object.', // Returned when linking two database records together - e.g linking a User to a Team
    'detached' => '분리 된 :Object.', // Returned when unlinking two database records - e.g removing a User from a Team
    'created'  => '생성 된 :Object.', // Returned when creating a new database record
    'updated'  => ':Object가 업데이트되었습니다.', // Returned when updating an existing database record
    'deleted'  => ':Object가 삭제되었습니다.', // Returned when deleting an existing database record

    'language_updated' => '언어가 성공적으로 업데이트되었습니다', // Returned when the user successfully switches their language

    'password_reset'   => '비밀번호 재설정 완료', // Returned when the user successfully resets their password

    'mode_active' => ':mode 활성', // Returned when switching the application mode. See misc.draft_mode and misc.live_mode
];
