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
class FlexModulBelajar
{
    private static $items = [
        '111' => [
            'photo' => 'https://kotlinlang.org/assets/images/open-graph/kotlin_250x250.png',
            'name' => 'Kotlin',
            'chapter' => 'Bab 1',
            'chapterName' => 'Pengenalan Variabel',
            'stock' => true,
        ],
        '112' => [
            'photo' => 'https://kotlinlang.org/assets/images/open-graph/kotlin_250x250.png',
            'name' => 'Kotlin',
            'chapter' => 'Bab 2',
            'chapterName' => 'Pembuatan Fungsi',
            'stock' => true,
        ],
    ];

    /**
     * Create sample shopping flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get()
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Shopping')
            ->setContents(new CarouselContainerBuilder([
                self::createItemBubble(111),
                self::createItemBubble(112)
            ]));
    }

    private static function createItemBubble($itemId)
    {
        $item = self::$items[$itemId];
        return BubbleContainerBuilder::builder()
            ->setHero(self::createItemHeroBlock($item))
            ->setBody(self::createItemBodyBlock($item))
            ->setFooter(self::createItemFooterBlock($item));
    }

    private static function createItemHeroBlock($item)
    {
        return ImageComponentBuilder::builder()
            ->setUrl($item['photo'])
            ->setSize(ComponentImageSize::FULL)
            ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
            ->setAspectMode(ComponentImageAspectMode::COVER);
    }

    private static function createItemBodyBlock($item)
    {
        $components = [];
        $components[] = TextComponentBuilder::builder()
            ->setText($item['name'])
            ->setWrap(true)
            ->setWeight(ComponentFontWeight::BOLD)
            ->setSize(ComponentFontSize::XL);

        $components[] = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::BASELINE)
            ->setContents([
                TextComponentBuilder::builder()
                    ->setText($item['chapter'])
                    ->setWrap(true)
                    ->setWeight(ComponentFontWeight::BOLD)
                    ->setSize(ComponentFontSize::XL)
                    ->setFlex(0),
                TextComponentBuilder::builder()
                    ->setText($item['chapterName'])
                    ->setWrap(true)
                    ->setWeight(ComponentFontWeight::BOLD)
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(0)
            ]);

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
                new MessageTemplateActionBuilder('MULAI BELAJAR', 'Mulai Belajar '.$item['name'].' '.$item['chapter'])
            );

        // $wishButton = ButtonComponentBuilder::builder()
        //     ->setAction(
        //         new UriTemplateActionBuilder(
        //             'Tambahkan ke Favorite',
        //             'https://example.com'
        //         )
        //     );

        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$cartButton]);
    }

    private static function createMoreBubble()
    {
        return BubbleContainerBuilder::builder()
            ->setBody(
                BoxComponentBuilder::builder()
                    ->setLayout(ComponentLayout::VERTICAL)
                    ->setSpacing(ComponentSpacing::SM)
                    ->setContents([
                        ButtonComponentBuilder::builder()
                            ->setFlex(1)
                            ->setGravity(ComponentGravity::CENTER)
                            ->setAction(new UriTemplateActionBuilder('See more', 'https://example.com'))
                    ])
            );
    }
}
