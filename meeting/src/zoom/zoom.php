<?php

/**
 * @desc: Open a Umeet meeting link without the browser opening.
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
        ->icon('src/imgs/new_meeting.png');

    $workflow->result()
        ->title('Enter ID')
        ->arg('join?confno=' . $keyword)
        ->valid(true)
        ->icon('src/imgs/join_meeting.png');
} elseif (is_numeric($keyword)) {
    $workflow->result()
        ->title('Enter ID')
        ->arg('join?confno=' . $keyword)
        ->valid(true)
        ->icon('src/imgs/join_meeting.png');
} elseif (false !== filter_var($keyword, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
    $meetingId = substr($keyword, strrpos($keyword, '/') + 1);
    $workflow->result()
        ->title('Enter ID')
        ->arg('join?confno=' . $meetingId)
        ->valid(true)
        ->icon('src/imgs/join_meeting.png');
} else {
    $workflow->result()
        ->title('Invalid meeting ID or link.')
        ->valid(true)
        ->icon('src/imgs/error.png');
}

echo $workflow->output();
