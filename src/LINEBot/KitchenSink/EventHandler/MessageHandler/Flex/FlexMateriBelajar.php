<?php

/**
 * Copyright 2018 LINE Corporation
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

namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Flex;

use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FlexMateriBelajar
{
    private static $items = [
        '1' => [
            'name' => 'Kotlin',
            'chapter' => 'Bab 1',
            'chapterName' => 'Pengenalan Variabel',
            'description' => 'Pembuatan variabel di Kotlin tidak teralalu formal seperti di Java.

            Pada Kotlin, kita boleh tidak menentukan/menyebutkan tipe datanya. Karena Kotlin sudah mampu mengenali tipe data dari nilai yang akan kita berikan.
            
            Pembuatan variabel diawali dengan kata kunci var dan val.
            \n
            Contoh membuat variabel dengan tipe data:',
            'stock' => true,
        ]
    ];

    /**
     * Create sample shopping flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get()
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Materi')
            ->setContents(new CarouselContainerBuilder([
                self::createItemBubble(1)
            ]));
    }

    private static function createItemBubble($itemId)
    {
        $item = self::$items[$itemId];
        return BubbleContainerBuilder::builder()
            ->setBody(self::createItemBodyBlock($item))
            ->setMateri(self::createItemMateriBlock($item))
            ->setFooter(self::createItemFooterBlock($item));
    }

    
    private static function createItemMateriBlock($item)
    {
        $components = [];
        $components[] = TextComponentBuilder::builder()
            ->setText($item['description'])
            ->setWrap(true);

        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents($components);
    }

    private static function createItemFooterBlock($item)
    {
        $color = $item['stock'] ? null : '#aaaaaa';
        $cartButton = ButtonComponentBuilder::builder()
            ->setStyle(ComponentButtonStyle::PRIMARY)
            ->setColor($color)
            ->setAction(
                new MessageTemplateActionBuilder('SELANJUTNYA', 'Mulai Quiz '.$item['name'].' '.$item['chapter'])
            );

        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$cartButton]);
    }

}
