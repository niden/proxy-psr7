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

namespace Phalcon\Tests\Unit\Http\Message\Uri;

use Codeception\Example;
use Phalcon\Proxy\Psr7\Http\Message\Uri;
use UnitTester;

class GetAuthorityCest
{
    /**
     * Tests Phalcon\Proxy\Psr7\Http\Message\Uri :: getAuthority()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-02-09
     */
    public function httpMessageUriGetAuthority(UnitTester $I, Example $example)
    {
        $I->wantToTest('Http\Message\Uri - getAuthority()');

        $uri = new Uri(
            $example[1]
        );

        $I->assertSame(
            $example[2],
            $uri->getAuthority()
        );
    }


    private function getExamples(): array
    {
        return [
            [
                'empty',
                '',
                '',
            ],

            [
                'default',
                'https://dev.phalcon.ld',
                'dev.phalcon.ld',
            ],

            [
                'with user info',
                'https://phalcon:secret@dev.phalcon.ld',
                'phalcon:secret@dev.phalcon.ld',
            ],

            [
                'with port',
                'https://dev.phalcon.ld:8080',
                'dev.phalcon.ld:8080',
            ],

            [
                'full',
                'https://phalcon:secret@dev.phalcon.ld:8080',
                'phalcon:secret@dev.phalcon.ld:8080',
            ],
        ];
    }
}
