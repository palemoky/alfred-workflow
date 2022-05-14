<?php

/**
 * @desc Open a Lark meeting link without the browser opening.
 * @author: Palemoky
 * @link: https://blog.palemoky.com
 */

use Alfred\Workflows\Workflow;

require 'vendor/autoload.php';

$workflow = new Workflow;

$keyword = $argv[1];
$keyword = trim(str_replace(' ', '', $keyword));

if (empty($keyword)) {
    $workflow->result()
        ->title('Start new meeting')
        ->arg('start')
        ->valid(true)
        ->icon('src/imgs/imgs/new_meeting.png');

    $workflow->result()
        ->title('Enter ID')
        ->arg($keyword)
        ->valid(true)
        ->icon('src/imgs/meeting_room.png');
} elseif (is_numeric($keyword)) {
    $workflow->result()
        ->title('Enter ID')
        ->arg($keyword)
        ->valid(true)
        ->icon('src/imgs/meeting_room.png');
} elseif (false !== filter_var($keyword, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
    $meetingId = substr($keyword, strrpos($keyword, '/') + 1);
    $workflow->result()
        ->title('Enter ID')
        ->arg($meetingId)
        ->valid(true)
        ->icon('src/imgs/meeting_room.png');
} else {
    $workflow->result()
        ->title('Invalid meeting ID or link.')
        ->valid(true)
        ->icon('src/imgs/error.png');
}

echo $workflow->output();
