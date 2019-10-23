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

namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler;

use LINE\LINEBot;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\KitchenSink\EventHandler;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Flex\FlexSampleRestaurant;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Flex\FlexSampleShopping;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Flex\FlexModulBelajar;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Flex\FlexMateriBelajar;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Util\UrlBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Evaluasi\EvaluasiCore;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class TextMessageHandler implements EventHandler
{
    /** @var LINEBot $bot */
    private $bot;
    /** @var \Monolog\Logger $logger */
    private $logger;
    /** @var \Slim\Http\Request $logger */
    private $req;
    /** @var TextMessage $textMessage */
    private $textMessage;

    /**
     * TextMessageHandler constructor.
     * @param $bot
     * @param $logger
     * @param \Slim\Http\Request $req
     * @param TextMessage $textMessage
     */
    public function __construct($bot, $logger, \Slim\Http\Request $req, TextMessage $textMessage)
    {
        $this->bot = $bot;
        $this->logger = $logger;
        $this->req = $req;
        $this->textMessage = $textMessage;
    }

    /**
     * @throws LINEBot\Exception\InvalidEventSourceException
     * @throws \ReflectionException
     */
    public function handle()
    {
        $starttime = microtime(true);
        $textOri = $this->textMessage->getText();
        $text = strtolower($this->textMessage->getText());
        $replyToken = $this->textMessage->getReplyToken();
        $this->logger->info("Got text message from $replyToken: $text");
        
        if (strpos($text, 'kotlin') !== false) {
            if (strpos($text, 'jawab') !== false) {
                if (strpos($text, '1') !== false || strpos($text, '2') !== false || strpos($text, '3') !== false) {
                    $jawaban = "";
                    $a = explode("\n",$text);
                    $aOri = explode("\n",$textOri);
                    for ($i = 0; $i < sizeof($a); $i++) {
                        
                        if (strpos($a[$i], 'quiz') !== false && strpos($a[$i], '1') !== false) {
                            
                        } else {
                            if ($i == 0) {
                                $jawaban = $aOri[$i];
                            } else {
                                $jawaban = $jawaban." ".$aOri[$i];
                            }
                        }
                    }
                    if (strpos($text, '3') !== false) {
                        $string = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter3.json");
                        $json_a = json_decode($string, true);
                        $jawaban = EvaluasiCore::search($json_a["answer"], $jawaban, $starttime);
                    } else if (strpos($text, '2') !== false) {
                        $string = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter2.json");
                        $json_a = json_decode($string, true);
                        $jawaban = EvaluasiCore::search($json_a["answer"], $jawaban, $starttime);
                    } else if (strpos($text, '1') !== false) {
                        $string = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter1.json");
                        $json_a = json_decode($string, true);
                        $jawaban = EvaluasiCore::search($json_a["answer"], $jawaban, $starttime);
                    }
                    $this->echoBack($replyToken, $jawaban);
                }
            } else  {
                if (strpos($text, '1') !== false) {
                    $flexMateriBuilder = FlexMateriBelajar::get(1);
                    $this->bot->replyMessage($replyToken, $flexMateriBuilder);
                } else if (strpos($text, '2') !== false) { 
                    $string = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter1.json");
                    $string2 = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter2.json");
                    $string3 = file_get_contents("/app/src/Evaluasi/Kotlin/Chapter3.json");
                    $json_a = json_decode($string, true);
                    $json_b = json_decode($string2, true);
                    $json_c = json_decode($string3, true);
                    $this->echoBack($replyToken,  "Untuk masuk ke materi selanjutnya jawab pertanyaan Materi Kotlin Chapter 1 : \n\n"."Soal No 1.\n".$json_a["question"]."\n\n"."Soal No 2.\n".$json_b["question"]."\n\n"."Soal No 3.\n".$json_c["question"]."\n\nContoh Cara Menjawab : \n\nJawab Quiz Kotlin Chapter 1 Soal 1 \n{Jawaban Anda}");
                } else {
                    $flexMessageBuilder = FlexModulBelajar::get();
                    $this->bot->replyMessage($replyToken, $flexMessageBuilder);
                }
            }
        } else {
            $this->echoBack($replyToken, "Mohon maaf kami masih belum bisa merespon chat anda, sabar nggih");
        }
    }

    /**
     * @param string $replyToken
     * @param string $text
     * @throws \ReflectionException
     */
    private function echoBack($replyToken, $text)
    {
        $this->logger->info("Returns echo message $replyToken: $text");
        $this->bot->replyText($replyToken, $text);
    }

    /**
     * @param $replyToken
     * @param $userId
     * @throws \ReflectionException
     */
    private function sendProfile($replyToken, $userId)
    {
        if (!isset($userId)) {
            $this->bot->replyText($replyToken, "Bot can't use profile API without user ID");
            return;
        }

        $response = $this->bot->getProfile($userId);
        if (!$response->isSucceeded()) {
            $this->bot->replyText($replyToken, $response->getRawBody());
            return;
        }

        $profile = $response->getJSONDecodedBody();
        $this->bot->replyText(
            $replyToken,
            'Display name: ' . $profile['displayName'],
            'Status message: ' . $profile['statusMessage']
        );
    }
}

// case 'profile':
//                 $userId = $this->textMessage->getUserId();
//                 $this->sendProfile($replyToken, $userId);
//                 break;
//             case 'bye':
//                 if ($this->textMessage->isRoomEvent()) {
//                     $this->bot->replyText($replyToken, 'Leaving room');
//                     $this->bot->leaveRoom($this->textMessage->getRoomId());
//                     break;
//                 }
//                 if ($this->textMessage->isGroupEvent()) {
//                     $this->bot->replyText($replyToken, 'Leaving group');
//                     $this->bot->leaveGroup($this->textMessage->getGroupId());
//                     break;
//                 }
//                 $this->bot->replyText($replyToken, 'Bot cannot leave from 1:1 chat');
//                 break;
//             case 'confirm':
//                 $this->bot->replyMessage(
//                     $replyToken,
//                     new TemplateMessageBuilder(
//                         'Confirm alt text',
//                         new ConfirmTemplateBuilder('Do it?', [
//                             new MessageTemplateActionBuilder('Yes', 'Yes!'),
//                             new MessageTemplateActionBuilder('No', 'No!'),
//                         ])
//                     )
//                 );
//                 break;
//             case 'buttons':
//                 $imageUrl = UrlBuilder::buildUrl($this->req, ['static', 'buttons', '1040.jpg']);
//                 $buttonTemplateBuilder = new ButtonTemplateBuilder(
//                     'My button sample',
//                     'Hello my button',
//                     $imageUrl,
//                     [
//                         new UriTemplateActionBuilder('Go to line.me', 'https://line.me'),
//                         new PostbackTemplateActionBuilder('Buy', 'action=buy&itemid=123'),
//                         new PostbackTemplateActionBuilder('Add to cart', 'action=add&itemid=123'),
//                         new MessageTemplateActionBuilder('Say message', 'hello hello'),
//                     ]
//                 );
//                 $templateMessage = new TemplateMessageBuilder('Button alt text', $buttonTemplateBuilder);
//                 $this->bot->replyMessage($replyToken, $templateMessage);
//                 break;
//             case 'carousel':
//                 $imageUrl = UrlBuilder::buildUrl($this->req, ['static', 'buttons', '1040.jpg']);
//                 $carouselTemplateBuilder = new CarouselTemplateBuilder([
//                     new CarouselColumnTemplateBuilder('foo', 'bar', $imageUrl, [
//                         new UriTemplateActionBuilder('Go to line.me', 'https://line.me'),
//                         new PostbackTemplateActionBuilder('Buy', 'action=buy&itemid=123'),
//                     ]),
//                     new CarouselColumnTemplateBuilder('buz', 'qux', $imageUrl, [
//                         new PostbackTemplateActionBuilder('Add to cart', 'action=add&itemid=123'),
//                         new MessageTemplateActionBuilder('Say message', 'hello hello'),
//                     ]),
//                 ]);
//                 $templateMessage = new TemplateMessageBuilder('Button alt text', $carouselTemplateBuilder);
//                 $this->bot->replyMessage($replyToken, $templateMessage);
//                 break;
//             case 'imagemap':
//                 $richMessageUrl = UrlBuilder::buildUrl($this->req, ['static', 'rich']);
//                 $imagemapMessageBuilder = new ImagemapMessageBuilder(
//                     $richMessageUrl,
//                     'This is alt text',
//                     new BaseSizeBuilder(1040, 1040),
//                     [
//                         new ImagemapUriActionBuilder(
//                             'https://store.line.me/family/manga/en',
//                             new AreaBuilder(0, 0, 520, 520)
//                         ),
//                         new ImagemapUriActionBuilder(
//                             'https://store.line.me/family/music/en',
//                             new AreaBuilder(520, 0, 520, 520)
//                         ),
//                         new ImagemapUriActionBuilder(
//                             'https://store.line.me/family/play/en',
//                             new AreaBuilder(0, 520, 520, 520)
//                         ),
//                         new ImagemapMessageActionBuilder(
//                             'URANAI!',
//                             new AreaBuilder(520, 520, 520, 520)
//                         )
//                     ]
//                 );
//                 $this->bot->replyMessage($replyToken, $imagemapMessageBuilder);
//                 break;
//             case 'restaurant':
//                 $flexMessageBuilder = FlexSampleRestaurant::get();
//                 $this->bot->replyMessage($replyToken, $flexMessageBuilder);
//                 break;
//             case 'shopping':
//                 $flexMessageBuilder = FlexSampleShopping::get();
//                 $this->bot->replyMessage($replyToken, $flexMessageBuilder);
//                 break;
//             case 'quickReply':
//                 $postback = new PostbackTemplateActionBuilder('Buy', 'action=quickBuy&itemid=222', 'Buy');
//                 $datetimePicker = new DatetimePickerTemplateActionBuilder(
//                     'Select date',
//                     'storeId=12345',
//                     'datetime',
//                     '2017-12-25t00:00',
//                     '2018-01-24t23:59',
//                     '2017-12-25t00:00'
//                 );

//                 $quickReply = new QuickReplyMessageBuilder([
//                     new QuickReplyButtonBuilder(new LocationTemplateActionBuilder('Location')),
//                     new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('Camera')),
//                     new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('Camera roll')),
//                     new QuickReplyButtonBuilder($postback),
//                     new QuickReplyButtonBuilder($datetimePicker),
//                 ]);

//                 $messageTemplate = new TextMessageBuilder('Text with quickReply buttons', $quickReply);
//                 $this->bot->replyMessage($replyToken, $messageTemplate);
//                 break;
//             default:
//                 $this->echoBack($replyToken, $text);
//                 break;