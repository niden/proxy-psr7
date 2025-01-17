<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Proxy\Psr7\Tests\Unit\Http\Message\Stream;

use Phalcon\Proxy\Psr7\Http\Message\Stream;
use RuntimeException;
use UnitTester;

class GetContentsCest
{
    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: getContents()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamGetContents(UnitTester $I)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $I->markTestSkipped('Need to fix Windows new lines...');
        }

        $I->wantToTest('Http\Message\Stream - getContents()');

        $fileName = dataDir('assets/stream/mit.txt');

        $stream = new Stream($fileName, 'rb');

        $I->openFile($fileName);

        $I->seeFileContentsEqual(
            $stream->getContents()
        );
    }

    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: getContents() - from position
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamGetContentsFromPosition(UnitTester $I)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $I->markTestSkipped('Need to fix Windows new lines...');
        }

        $I->wantToTest('Http\Message\Stream - getContents() - from position');
        $fileName = dataDir('assets/stream/mit.txt');
        $stream   = new Stream($fileName, 'rb');

        $stream->seek(626);
        $expected = 'THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY '
            . 'KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE '
            . 'WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR '
            . 'PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS '
            . 'OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR '
            . 'OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR '
            . 'OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE '
            . 'SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.'
            . PHP_EOL;
        $actual   = $stream->getContents();
        $I->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: getContents() - exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamGetContentsException(UnitTester $I)
    {
        $I->wantToTest('Http\Message\Stream - getContents() - exception');
        $I->expectThrowable(
            new RuntimeException(
                'The resource is not readable.'
            ),
            function () {
                $fileName = dataDir('assets/stream/mit-empty.txt');
                $stream   = new Stream($fileName, 'wb');

                $actual = $stream->getContents();
            }
        );
    }
}
