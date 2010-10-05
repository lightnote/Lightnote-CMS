<?php
/*
 * Copyright (c) 2007-2010, Dmitry Monin <dmitry.monin [at] lightnote [dot] org>
 * Permission to use and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * You should have received a copy of the Lightnote CMS End-User License Agreement
 * along with this program.  If not, see <http://www.lightnote.org/license.html>.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL
 * WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR
 * BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES
 * OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS,
 * WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION,
 * ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS
 * SOFTWARE.
 */


namespace Lightnote\Util;

/**
 * String class
 */
class String
{
    public static function indexOf($haystack, $needle, $offset = 0)
    {
        if(\function_exists('\mb_strpos'))
        {
            return \mb_strpos($haystack, $needle, $offset, 'utf-8');
        }
        else
        {
            return \strpos(\utf8_decode($haystack), $needle, $offset);
        }
    }

    public static function length($str)
    {
        if(\function_exists('\mb_strlen'))
        {
            return \mb_strlen($str, 'utf-8');
        }
        else
        {
            return \strlen(\utf8_decode($str));
        }
    }
    
    public static function subString($str, $start, $length = null)
    {
        if(\function_exists('\mb_substr'))
        {
            if($length === null)
            {
                $length = \mb_strlen($str, 'utf-8');
            }
            return \mb_substr($str, $start, $length, 'utf-8');
        }
        else
        {
            if($length === null)
            {
                $length = \strlen($str);
            }

            return \utf8_encode(\substr(\utf8_decode($str), $start, $length));
        }
    }
}