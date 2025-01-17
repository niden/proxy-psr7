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
use UnitTester;

class ToStringCest
{
    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: __toString()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamToString(UnitTester $I)
    {
        $I->wantToTest('Http\Message\Stream - __toString()');

        $fileName = dataDir('assets/stream/mit.txt');
        $expected = file_get_contents($fileName);
        $stream   = new Stream($fileName, 'rb');

        $I->assertSame(
            $expected,
            (string) $stream
        );

        $I->assertSame(
            $expected,
            $stream->__toString()
        );
    }
}
