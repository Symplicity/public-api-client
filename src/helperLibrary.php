<?php
function getClient()
{
    return new Symplicity\PublicApiClient\ApiClient(API['INSTANCE'], API['TOKEN']);
}

function outputResults($label, $data)
{
    $delim = '------------------------------';
    echo "$delim $label $delim\n\n";
    print_r($data);
}

function getBulkJobData()
{
    $data = [
        getJobData([
            'postingDate' => '2017-09-12',
            'created' => '2017-09-28 00:45:54',
            'approval_date' => '2017-09-28 00:45:54',
            'expirationDate' => '2018-09-12'
        ])
    ];

    $data[] = getJobData([
        'title' => null,
        'annuallyRecurring' => null
    ]);
    unset($data[1]['locations'][0]['state']);

    $data[] = getJobData();

    return $data;
}

function getJobData($override = [])
{
    $start = date('Y-m-d', strtotime('-7 days', time()));
    $end = date('Y-m-d', strtotime('+6 days', time()));

    $data = [
        'title' => 'Supervisor Administrativo',
        'contactInformation' => 'Cargill',
        'locations' => [
            [
                'country' => 'US',
                'state' => 'US-VA',
                'nationwide' => true
            ],
            [
                'city' => 'Fairfax',
                'state' => 'US-VA',
                'country' => 'US'
            ]
        ],
        'postingDate' => $start,
        'approval_date' => "$start 00:45:54",
        'expirationDate' => $end,
        'description' => "<p>Help our department run smoothly, ensuring transparency and efficiency in all transactions.</p>",
        'importedId' => '3a481fdb3fe15707',
        'source' => 'curated',
        'isOCR' => false,
        'approved' => true,
        'annuallyRecurring' => false,
        'restrictApplicants' => false,
        'showContactInformation' => true,
        'resumeSubmissionMethod' => 'other',
        'employer' => '2d5c86385d43aebb8368fc8edbb6acf9',
        'contact' => 'a2c186104eb73903d6fdb6f75332ab01',
        'howToApply' => 'https://job-openings.monster.com/Outside-Plant-Engineer-Murfreesboro-TN-US-Verizon/31/f744ef92-3f51-4b64-a058-d1d6eff4eec0',
        'types' => [1],
        'created' => "$start 00:45:54"
    ];

    if (count($override)) {
        $data = array_merge($data, $override);
    }

    return $data;
}
