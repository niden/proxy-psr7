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
use Phalcon\Proxy\Psr7\Tests\Fixtures\Http\Message\StreamFixture;
use RuntimeException;
use UnitTester;

class SeekCest
{
    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: seek()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamSeek(UnitTester $I)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $I->markTestSkipped('Need to fix Windows new lines...');
        }

        $I->wantToTest('Http\Message\Stream - seek()');
        $fileName = dataDir('assets/stream/mit.txt');
        $stream   = new Stream($fileName, 'rb');

        $stream->seek(64);
        $expected = 'Permission is hereby granted, free of charge, to any '
            . 'person obtaining a copy of this software and associated '
            . 'documentation files (the "Software"), to deal in the '
            . 'Software without restriction, including without limitation '
            . 'the rights to use, copy, modify, merge, publish, distribute, '
            . 'sublicense, and/or sell copies of the Software, and to permit '
            . 'persons to whom the Software is furnished to do so, subject '
            . 'to the following conditions:';
        $actual   = $stream->read(432);
        $I->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: seek() - after file size
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamSeekAfterFileSize(UnitTester $I)
    {
        $I->wantToTest('Http\Message\Stream - seek() - after file size');
        $fileName = dataDir('assets/stream/mit.txt');
        $stream   = new Stream($fileName, 'rb');

        $stream->seek(10240);
        $expected = '';
        $actual   = $stream->read(1);
        $I->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Stream :: seek() - exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function httpMessageStreamSeekException(UnitTester $I)
    {
        $I->wantToTest('Http\Message\Stream - seek() - exception');
        $fileName = dataDir('assets/stream/mit.txt');
        $stream   = new StreamFixture($fileName, 'rb');

        $I->expectThrowable(
            new RuntimeException('The resource is not seekable.'),
            function () use ($stream) {
                $stream->seek(10240);
            }
        );
    }
}
