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

namespace Lightnote;

/**
 * Factory class
 *
 *
 */
class Factory implements View\IViewFactory
{
    /**
     *
     * @var \Lightnote\Config
     */
    protected $config;


    /**
     *
     * @param \Lightnote\Config $config 
     */
    public function __construct(\Lightnote\Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Localization\ILocalization
     */
    public function getLocalization()
    {
        // @todo insert correct path to localization file here
        return new Localization\GetText\Localization('enUS.mo');
    }

    /**
     *
     * @param string $modelName
     * @return Repository\IRepository
     */
    public function getRepository($modelName)
    {

    }

    /**
     *
     * @param int $id
     * @param DomainModel\Locale $locale
     * @return DomainModel\PageTranslation
     */
    public function getPageTranslation($id, DomainModel\Locale $locale)
    {
        
    }

    public function getView($path)
    {
        return new \Lightnote\View\PhpView($path);
    }
}