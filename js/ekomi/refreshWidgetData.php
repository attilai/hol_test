<?php

require_once(__DIR__ . '/includes/config.php');

refreshDataCache();

function refreshDataCache() {
    $handle = fopen(__DIR__.'/data/data.json', 'w');
    fwrite($handle, '');
    fwrite($handle, json_encode(getWidgetData()));
    chmod(__DIR__. '/data/data.json', 0777);
}

function getWidgetData() {
    $rawWidgetRatingData = file_get_contents(WIDGET_BASE_PATH . API_ID . WIDGET_RATING_PATH);
    $splittedWidgetRatingData = preg_split('~\|~', $rawWidgetRatingData);
    $widgetReviewData = getRandomReviews();
    return  array(
        'ratingData' => $splittedWidgetRatingData,
        'reviewData' => $widgetReviewData
    );
}

/**
 * @return array
 */
function getRandomReviews() {
    $widgetReviewData = array();
    $handle = fopen(API_BASE_PATH . 'getFeedback?interface_id=' . API_ID . '&interface_pw=' . API_KEY .
        '&version=cust_1.0.0&type=csv&charset=utf-8&filter=positive&range=1m', 'r');
    $i = 0;
    while($row = fgetcsv($handle, 4096, ',', '"')) {
        if($i < 30) {
            if(strlen($row[3]) >= 32) {
                $row[3] = substr($row[3], 0, 32);
                $lastSpace = strrpos($row[3], ' ', -1);
                $row[3] = substr($row[3], 0, $lastSpace - 1) . '...';

            }
            $widgetReviewData[] = array(
                'rating' => $row[2],
                'review' => str_replace('\n', ' ', $row[3])
            );
        }
        $i++;
    }
    return $widgetReviewData;
}