<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Ustring\Test\Unit;

use Hoa\Test;
use Hoa\Ustring as LUT;

/**
 * Class \Hoa\Ustring\Test\Unit\Ustring.
 *
 * Test suite of the string class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Ustring extends Test\Unit\Suite
{
    public function case_check_mbstring()
    {
        $this
            ->given($this->function->function_exists = true)
            ->then
                ->boolean(LUT::checkMbString())
                    ->isTrue();
    }

    public function case_check_no_mbstring()
    {
        $this
            ->given(
                $this->function->function_exists = function ($name) {
                    return 'mb_substr' !== $name;
                }
            )
            ->exception(function () {
                new LUT();
            })
                ->isInstanceOf('Hoa\Ustring\Exception');
    }

    public function case_append_ltr()
    {
        $this
            ->given($string = new LUT('je'))
            ->when($result = $string->append(' t\'aime'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_append_rtl()
    {
        $this
            ->given($string = new LUT('أ'))
            ->when($result = $string->append('حبك'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك');
    }

    public function case_prepend_ltr()
    {
        $this
            ->given($string = new LUT(' t\'aime'))
            ->when($result = $string->prepend('je'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_prepend_rtl()
    {
        $this
            ->given($string = new LUT('ك'))
            ->when($result = $string->prepend('أحب'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك');
    }

    public function case_pad_beginning_ltr()
    {
        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('👍 💩 😄 ❤️ 👍 je t\'aime');
    }

    public function case_pad_beginning_rtl()
    {
        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('👍 💩 😄 ❤️ 👍 💩 😄 ❤أحبك');
    }

    public function case_pad_end_ltr()
    {
        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime👍 💩 😄 ❤️ 👍 ');
    }

    public function case_pad_end_rtl()
    {
        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->pad(20, '👍 💩 😄 ❤️ ', LUT::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أحبك👍 💩 😄 ❤️ 👍 💩 😄 ❤');
    }

    public function case_compare_no_collator()
    {
        $this
            ->given(
                $this->function->class_exists = function ($name) {
                    return 'Collator' !== $name;
                },
                $string = new LUT('b')
            )
            ->case_compare();
    }

    public function case_compare()
    {
        $this
            ->given($string = new LUT('b'))
            ->when($result = $string->compare('a'))
            ->then
                ->integer($result)
                    ->isEqualTo(1)

            ->when($result = $string->compare('b'))
            ->then
                ->integer($result)
                    ->isEqualTo(0)

            ->when($result = $string->compare('c'))
            ->then
                ->integer($result)
                    ->isEqualTo(-1);
    }

    public function case_collator()
    {
        $this
            ->given(
                $this->function->setlocale = 'fr_FR',
                $collator = LUT::getCollator()
            )
            ->when($result = $collator->getLocale(\Locale::VALID_LOCALE))
            ->then
                ->string($result)
                    ->isEqualTo('fr');
    }

    public function case_safe_unsafe_pattern()
    {
        $this
            ->given($pattern = '/foo/i')
            ->when($result = LUT::safePattern($pattern))
            ->then
                ->string($result)
                    ->isEqualto('/foo/iu');
    }

    public function case_safe_safe_pattern()
    {
        $this
            ->given($pattern = '/foo/ui')
            ->when($result = LUT::safePattern($pattern))
            ->then
                ->string($result)
                    ->isEqualto('/foo/ui');
    }

    public function case_match_default()
    {
        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([
                        0 => '💩'
                    ]);
    }

    public function case_match_offset()
    {
        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches, 0, 0))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([0 => '💩'])

            ->when($result = $string->match($pattern, $matches, 0, 4))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([0 => '💩'])

            ->when($result = $string->match($pattern, $matches, 0, 5))
            ->then
                ->integer($result)
                    ->isEqualTo(0)
                ->array($matches)
                    ->isEmpty();
    }

    public function case_match_with_offset()
    {
        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar')
            )
            ->when($result = $string->match($pattern, $matches, $string::WITH_OFFSET))
            ->then
                ->integer($result)
                    ->isEqualTo(1)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => 4
                        ]
                    ]);
    }

    public function case_match_all_default()
    {
        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, 0, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_match_all_with_offset()
    {
        $this
            ->given(
                $pattern = '/💩/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::WITH_OFFSET, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => [
                                0 => '💩',
                                1 => 4
                            ],
                            1 => [
                                0 => '💩',
                                1 => 13
                            ]
                        ]
                    ]);
    }

    public function case_match_all_grouped_by_pattern()
    {
        $this
            ->given(
                $pattern = '/(💩)/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::GROUP_BY_PATTERN, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ],
                        1 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_match_all_grouped_by_tuple()
    {
        $this
            ->given(
                $pattern = '/(💩)/u',
                $string  = new LUT('foo 💩 bar 💩 baz')
            )
            ->when($result = $string->match($pattern, $matches, $string::GROUP_BY_TUPLE, 0, true))
            ->then
                ->integer($result)
                    ->isEqualTo(2)
                ->array($matches)
                    ->isEqualTo([
                        0 => [
                            0 => '💩',
                            1 => '💩'
                        ],
                        1 => [
                            0 => '💩',
                            1 => '💩'
                        ]
                    ]);
    }

    public function case_replace()
    {
        $this
            ->given($string = new LUT('❤️ 💩 💩'))
            ->when($result = $string->replace('/💩/u', '😄'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️ 😄 😄');
    }

    public function case_replace_limited()
    {
        $this
            ->given($string = new LUT('❤️ 💩 💩'))
            ->when($result = $string->replace('/💩/u', '😄', 1))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️ 😄 💩');
    }

    public function case_split_default()
    {
        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/'))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️',
                        1 => '❤️',
                        2 => '❤️'
                    ]);
    }

    public function case_split_default_limited()
    {
        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', 1))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️💩❤️💩❤️'
                    ]);
    }

    public function case_split_with_delimiters()
    {
        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', -1, $string::WITH_DELIMITERS))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => '❤️',
                        1 => '❤️',
                        2 => '❤️'
                    ]);
    }

    public function case_split_with_offset()
    {
        $this
            ->given($string = new LUT('❤️💩❤️💩❤️'))
            ->when($result = $string->split('/💩/', -1, $string::WITH_OFFSET))
            ->then
                ->array($result)
                    ->isEqualTo([
                        0 => [
                            0 => '❤️',
                            1 => 0
                        ],
                        1 => [
                            0 => '❤️',
                            1 => 10
                        ],
                        2 => [
                            0 => '❤️',
                            1 => 20
                        ]
                    ]);
    }

    public function case_iterator_ltr()
    {
        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = iterator_to_array($string))
            ->then
                ->array($result)
                    ->isEqualTo([
                        'j',
                        'e',
                        ' ',
                        't',
                        '\'',
                        'a',
                        'i',
                        'm',
                        'e'
                    ]);
    }

    public function case_iterator_rtl()
    {
        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = iterator_to_array($string))
            ->then
                ->array($result)
                    ->isEqualTo([
                        'أ',
                        'ح',
                        'ب',
                        'ك'
                    ]);
    }

    public function case_to_lower()
    {
        $this
            ->given($string = new LUT('Σ \'ΑΓΑΠΏ'))
            ->when($result = $string->toLowerCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('σ \'αγαπώ')

            ->given($string = new LUT('JE T\'AIME'))
            ->when($result = $string->toLowerCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('je t\'aime');
    }

    public function case_to_upper()
    {
        $this
            ->given($string = new LUT('σ \'αγαπώ'))
            ->when($result = $string->toUpperCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Σ \'ΑΓΑΠΏ')

            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string->toUpperCase())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('JE T\'AIME');
    }

    public function case_trim_default()
    {
        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩'))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️');
    }

    public function case_trim_beginning()
    {
        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩', $string::BEGINNING))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('❤️💩💩');
    }

    public function case_trim_end()
    {
        $this
            ->given($string = new LUT('💩💩❤️💩💩'))
            ->when($result = $string->trim('💩', $string::END))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('💩💩❤️');
    }

    public function case_offset_get_ltr()
    {
        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = $string[0])
            ->then
                ->string($result)
                    ->isEqualTo('j')

            ->when($result = $string[-1])
            ->then
                ->string($result)
                    ->isEqualTo('e');
    }

    public function case_offset_get_rtl()
    {
        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string[0])
            ->then
                ->string($result)
                    ->isEqualTo('أ')

            ->when($result = $string[-1])
            ->then
                ->string($result)
                    ->isEqualTo('ك');
    }

    public function case_offset_set()
    {
        $this
            ->given($string = new LUT('أحبﻙ'))
            ->when($string[-1] = 'ك')
            ->then
                ->string((string) $string)
                    ->isEqualTo('أحبك');
    }

    public function case_offset_unset()
    {
        $this
            ->given($string = new LUT('أحبك😄'))
            ->when(function () use ($string) {
                unset($string[-1]);
            })
            ->then
                ->string((string) $string)
                    ->isEqualTo('أحبك');
    }

    public function case_reduce()
    {
        $this
            ->given($string = new LUT('أحبك'))
            ->when($result = $string->reduce(0, 1))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('أ');
    }

    public function case_count()
    {
        $this
            ->given($string = new LUT('je t\'aime'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(9)

            ->given($string = new LUT('أحبك'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(4)

            ->given($string = new LUT('💩'))
            ->when($result = count($string))
            ->then
                ->integer($result)
                    ->isEqualTo(1);
    }

    public function case_byte_at()
    {
        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getByteAt(0))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xf0)

            ->when($result = $string->getByteAt(1))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0x9f)

            ->when($result = $string->getByteAt(2))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0x92)

            ->when($result = $string->getByteAt(3))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xa9)

            ->when($result = $string->getByteAt(-1))
            ->then
                ->integer(ord($result))
                    ->isEqualTo(0xa9);
    }

    public function case_bytes_length()
    {
        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getBytesLength())
            ->then
                ->integer($result)
                    ->isEqualTo(4);
    }

    public function case_get_width()
    {
        $this
            ->given($string = new LUT('💩'))
            ->when($result = $string->getWidth())
            ->then
                ->integer($result)
                    ->isEqualTo(1)

            ->given($string = new LUT('習'))
            ->when($result = $string->getWidth())
            ->then
                ->integer($result)
                    ->isEqualTo(2);
    }

    public function case_get_char_direction()
    {
        $this
            ->when($result = LUT::getCharDirection('A'))
            ->then
                ->integer($result)
                    ->isEqualTo(LUT::LTR)

            ->when($result = LUT::getCharDirection('ا'))
            ->then
                ->integer($result)
                    ->isEqualTo(LUT::RTL);
    }

    public function case_get_char_width()
    {
        $this
            ->given(
                $data = [
                    // 8-bit control character.
                    [0x0,    0],
                    [0x19,  -1],
                    [0x7f,  -1],
                    [0x9f,  -1],

                    // Regular.
                    [0xa0,   1],

                    // Non-spacing characters mark.
                    [0x300,  0], // in Mn
                    [0x488,  0], // in Me
                    [0x600,  0], // in Cf
                    [0xad,   1], // in Cf, but the only exception
                    [0x1160, 0],
                    [0x11ff, 0],
                    [0x200b, 0],

                    // To test the last return statement.
                    [0x1100, 2],
                    [0x2160, 1],
                    [0x3f60, 2],
                    [0x303f, 1],
                    [0x2329, 2],
                    [0xaed0, 2],
                    [0x232a, 2],
                    [0xffa4, 1],
                    [0xfe10, 2],
                    [0xfe30, 2],
                    [0xff00, 2],
                    [0xf900, 2]
                ]
            )
            ->when(function () use ($data) {
                foreach ($data as $datum) {
                    list($code, $width) = $datum;

                    $this
                        ->when($result = LUT::getCharWidth(LUT::fromCode($code)))
                        ->then
                            ->integer($result)
                                ->isEqualTo($width);
                }
            });
    }

    public function case_is_char_printable()
    {
        $this
            ->when($result = LUT::isCharPrintable(LUT::fromCode(0x7f)))
            ->then
                ->boolean($result)
                    ->isFalse()

            ->when($result = LUT::isCharPrintable(LUT::fromCode(0xa0)))
            ->then
                ->boolean($result)
                    ->isTrue()

            ->when($result = LUT::isCharPrintable(LUT::fromCode(0x1100)))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_from_code()
    {
        $this
            // U+0000 to U+007F
            ->when($result = LUT::fromCode(0x7e))
            ->then
                ->string($result)
                    ->isEqualTo('~')

            // U+0080 to U+07FF
            ->when($result = LUT::fromCode(0xa7))
            ->then
                ->string($result)
                    ->isEqualTo('§')

            // U+0800 to U+FFFF
            ->when($result = LUT::fromCode(0x1207))
            ->then
                ->string($result)
                    ->isEqualTo('ሇ')

            // U+10000 to U+10FFFF
            ->when($result = LUT::fromCode(0x1f4a9))
            ->then
                ->string($result)
                    ->isEqualTo('💩');
    }

    public function case_to_code()
    {
        $this
            // U+0000 to U+007F
            ->when($result = LUT::toCode('~'))
            ->then
                ->integer($result)
                    ->isEqualTo(0x7e)

            // U+0080 to U+07FF
            ->when($result = LUT::toCode('§'))
            ->then
                ->integer($result)
                    ->isEqualTo(0xa7)

            // U+0800 to U+FFFF
            ->when($result = LUT::toCode('ሇ'))
            ->then
                ->integer($result)
                    ->isEqualTo(0x1207)

            // U+10000 to U+10FFFF
            ->when($result = LUT::toCode('💩'))
            ->then
                ->integer($result)
                    ->isEqualTo(0x1f4a9);
    }

    public function case_to_binary_code()
    {
        $this
            // U+0000 to U+007F
            ->when($result = LUT::toBinaryCode('~'))
            ->then
                ->string($result)
                    ->isEqualTo('01111110')

            // U+0080 to U+07FF
            ->when($result = LUT::toBinaryCode('§'))
            ->then
                ->string($result)
                    ->isEqualTo('1100001010100111')

            // U+0800 to U+FFFF
            ->when($result = LUT::toBinaryCode('ሇ'))
            ->then
                ->string($result)
                    ->isEqualTo('111000011000100010000111')

            // U+10000 to U+10FFFF
            ->when($result = LUT::toBinaryCode('💩'))
            ->then
                ->string($result)
                    ->isEqualTo('11110000100111111001001010101001');
    }

    public function case_transcode_no_iconv()
    {
        $this
            ->given(
                $this->function->function_exists = function ($name) {
                    return 'iconv' !== $name;
                }
            )
            ->exception(function () {
                LUT::transcode('foo', 'UTF-8');
            })
                ->isInstanceOf('Hoa\Ustring\Exception');
    }

    public function case_transcode_and_isUtf8()
    {
        $this
            ->given($uΣ = 'Σ')
            ->when($Σ = LUT::transcode($uΣ, 'UTF-8', 'UTF-16'))
            ->then
                ->string($Σ)
                    ->isNotEqualTo($uΣ)
                ->boolean(LUT::isUtf8($Σ))
                    ->isFalse()

            ->when($Σ = LUT::transcode($Σ, 'UTF-16', 'UTF-8'))
                ->string($Σ)
                    ->isEqualTo($uΣ)
                ->boolean(LUT::isUtf8($Σ))
                    ->isTrue()
                ->boolean(LUT::isUtf8($uΣ))
                    ->isTrue();
    }

    public function case_to_ascii_no_transliterator_no_normalizer()
    {
        $this
            ->given(
                $this->function->class_exists = function ($name) {
                    return false === in_array($name, ['Transliterator', 'Normalizer']);
                },
                $string = new LUT('Un été brûlant sur la côte')
            )
            ->exception(function () use ($string) {
                $string->toAscii();
            })
                ->isInstanceOf('Hoa\Ustring\Exception');
    }

    public function case_to_ascii_no_transliterator_no_normalizer_try()
    {
        $this
            ->given(
                $this->function->class_exists = function ($name) {
                    return false === in_array($name, ['Transliterator', 'Normalizer']);
                },
                $string = new LUT('Un été brûlant sur la côte')
            )
            ->when($result = $string->toAscii(true))
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Un ete brulant sur la cote');
    }

    public function case_to_ascii_no_transliterator()
    {
        $this
            ->given(
                $this->function->class_exists = function ($name) {
                    return 'Transliterator' !== $name;
                },
                $string = new LUT('Un été brûlant sur la côte')
            )
            ->when($result = $string->toAscii())
            ->then
                ->object($result)
                    ->isIdenticalTo($string)
                ->string((string) $result)
                    ->isEqualTo('Un ete brulant sur la cote');
    }

    public function case_to_ascii()
    {
        $this
            ->given(
                $strings = [
                    'Un été brûlant sur la côte'
                    => 'Un ete brulant sur la cote',

                    'Αυτή είναι μια δοκιμή'
                    => 'Aute einai mia dokime',

                    'أحبك'
                    => 'ahbk',

                    'キャンパス'
                    => 'kyanpasu',

                    'биологическом'
                    => 'biologiceskom',

                    '정, 병호'
                    => 'jeong, byeongho',

                    'ますだ, よしひこ'
                    => 'masuda, yoshihiko',

                    'मोनिच'
                    => 'monica',

                    'क्ष'
                    => 'ksa',

                    'أحبك 😀'
                    => 'ahbk (grinning face)',

                    '∀ i ∈ ℕ'
                    => '(for all) i (element of) N'
                ]
            )
            ->when(function () use ($strings) {
                foreach ($strings as $original => $asciied) {
                    $this
                        ->given($string = new LUT($original))
                        ->when($result = $string->toAscii())
                        ->then
                            ->object($result)
                                ->isIdenticalTo($string)
                            ->string((string) $result)
                                ->isEqualTo($asciied);
                }
            });
    }

    public function case_copy()
    {
        $this
            ->given($string = new LUT('foo'))
            ->when($result = $string->copy())
            ->then
                ->object($result)
                    ->isEqualTo($string);
    }

    public function case_toString()
    {
        $this
            ->given($datum = $this->sample($this->realdom->regex('/\w{7,42}/')))
            ->when($result = new LUT($datum))
            ->then
                ->castToString($result)
                    ->isEqualTo($datum);
    }
}
