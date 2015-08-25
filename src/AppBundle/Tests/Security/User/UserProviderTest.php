<?php

namespace AppBundle\Tests\Security\User;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use AppBundle\Security\User\UserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $user;

    protected function setUp()
    {
        $this->userRepository = $this->getMock(UserRepository::class);
        $this->userProvider = new UserProvider($this->userRepository);

        $this->user = $this->getMock(User::class);
        $this->user->expects($this->any())
            ->method('getUsername')
            ->willReturn('kuba');
    }

    public function testItIsAUserProvider()
    {
        $this->assertInstanceOf(UserProviderInterface::class, $this->userProvider);
    }

    public function testItLoadsUserByUsername()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->with('kuba')
            ->willReturn($this->user);

        $this->assertSame($this->user, $this->userProvider->loadUserByUsername('kuba'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testItThrowsAUsernameNotFoundExceptionIfUserCannotBeLoaded()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->with('kuba')
            ->willReturn(null);

        $this->userProvider->loadUserByUsername('kuba');
    }

    public function testItRefreshesTheUser()
    {
        $reloadedUser = $this->getMock(User::class);
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->with('kuba')
            ->willReturn($reloadedUser);

        $this->assertSame($reloadedUser, $this->userProvider->refreshUser($this->user));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testItThrowsAnUnsupportedUserExceptionForNonAppBundleUser()
    {
        $this->userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->willReturn($this->getMock(User::class));

        $this->userProvider->refreshUser($this->getMock(UserInterface::class));
    }

    public function testItSupportsTheAppBundleUser()
    {
        $this->assertTrue($this->userProvider->supportsClass(User::class));
    }

    public function testItDoesNotSupportOtherClasses()
    {
        $this->assertFalse($this->userProvider->supportsClass(UserInterface::class));
    }
}