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

namespace Lightnote\Routing\Constrain;

/**
 * RegExpContrain class
 *
 *
 */
class RegExpConstrain implements IConstrain
{
    /**
     *
     * @var string
     */
    private $paramName;

    /**
     *
     * @var string
     */
    private $pattern;



    /**
     *
     * @param string $paramName name of parameter to be validated
     * @param string $pattern Regular expression in PECL format, i.e. /^[a-z]+$/
     */
    public function __construct($paramName, $pattern)
    {
        $this->paramName = $paramName;
        $this->pattern = $pattern;
    }

    /**
     *
     * @param \Lightnote\Http\HttpContext $httpContext
     * @param \Lightnote\Routing\Route $route
     * @param string $paramName
     * @param array $values
     */
    public function match(\Lightnote\Http\HttpContext $httpContext, \Lightnote\Routing\Route $route, $values)
    {
        return \preg_match($this->pattern, (string)$values[$this->paramName]);
    }
}