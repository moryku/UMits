<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace LINE\LINEBot\KitchenSink;

class Setting
{
    public static function getSetting()
    {
        return [
            'settings' => [
                'displayErrorDetails' => true, // set to false in production

                'logger' => [
                    'name' => 'slim-app',
                    'path' => __DIR__ . '/../../../logs/app.log',
                ],

                'bot' => [
                    'channelToken' => '+xxHRu/hJ9ApTpJFcEXdyI+xhEitXaMNbJO/JKp67ae7WiZb0CgkTx7VY8W/GxD9HQySD4t5SfK9ZzfRZ6zibjSjtGMIat07PlnMfyNbItHWl57mX7YcK7FgJx9VqzUWtIvnl/QwFl1cTISpmdShngdB04t89/1O/w1cDnyilFU=',
                    'channelSecret' => '3eb4814fa7b19674eb17eb2790cb73b2',
                ],

                'apiEndpointBase' => 'https://umits.herokuapp.com/',
            ],
        ];
    }
}
