<?php

return array(
    'environments' => array(
        'local' => array(
            'provider' => 'Lasset\Providers\Host',
            'options' => array(
                'baseUrl' => '/',
            ),
        ),
    ),
    'default' => 'local',
);
