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
            'chapter' => 'Bab 1 ',
            'chapterName' => 'Variabel',
            'desc' => [
                'Pengertian Variabel',
                'Variabel merupakan simbol yang digunakan untuk menyimpan sebuah nilai. Sedangkan tipe data adalah jenis nilai yang akan disimpan.',
            ],
            'stock' => true,
        ],
        '2' => [
            'name' => 'Kotlin',
            'chapter' => 'Bab 2 ',
            'chapterName' => 'Pembuatan Fungsi',
            'stock' => true,
        ],
    ];

    /**
     * Create sample shopping flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get($materNumber)
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Shopping')
            ->setContents(new CarouselContainerBuilder([
                self::createItemBubble($materNumber)
            ]));
    }

    private static function createItemBubble($itemId)
    {
        $item = self::$items[$itemId];
        return BubbleContainerBuilder::builder()
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
        
        for ($i = 0; $i < sizeof($item['desc']; $i++)) {
            $components[] = TextComponentBuilder::builder()
                ->setText($item['desc'][$i])
                ->setWrap(true)
                ->setWeight(ComponentFontWeight::REGULAR)
                ->setSize(ComponentFontSize::SM);
        }

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
                new MessageTemplateActionBuilder('MATERI SELANJUTNYA', 'Mulai Belajar '.$item['name'].' '.$item['chapter'])
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
