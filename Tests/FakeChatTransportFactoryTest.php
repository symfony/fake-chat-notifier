<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Bridge\FakeChat\Tests;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Bridge\FakeChat\FakeChatTransportFactory;
use Symfony\Component\Notifier\Test\TransportFactoryTestCase;
use Symfony\Component\Notifier\Transport\TransportFactoryInterface;

final class FakeChatTransportFactoryTest extends TransportFactoryTestCase
{
    /**
     * @return FakeChatTransportFactory
     */
    public function createFactory(): TransportFactoryInterface
    {
        return new FakeChatTransportFactory($this->createMock(MailerInterface::class));
    }

    public function createProvider(): iterable
    {
        yield [
            'fakechat+email://default?to=recipient@email.net&from=sender@email.net',
            'fakechat+email://default?to=recipient@email.net&from=sender@email.net',
        ];

        yield [
            'fakechat+email://mailchimp?to=recipient@email.net&from=sender@email.net',
            'fakechat+email://mailchimp?to=recipient@email.net&from=sender@email.net',
        ];
    }

    public function missingRequiredOptionProvider(): iterable
    {
        yield 'missing option: from' => ['fakechat+email://default?to=recipient@email.net'];
        yield 'missing option: to' => ['fakechat+email://default?from=sender@email.net'];
    }

    public function supportsProvider(): iterable
    {
        yield [true, 'fakechat+email://default?to=recipient@email.net&from=sender@email.net'];
        yield [false, 'somethingElse://default?to=recipient@email.net&from=sender@email.net'];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield 'missing from' => ['fakechat+email://default?to=recipient@email.net'];
        yield 'missing to' => ['fakechat+email://default?from=recipient@email.net'];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield ['somethingElse://default?to=recipient@email.net&from=sender@email.net'];
    }
}
