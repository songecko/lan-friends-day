<?php

namespace Odiseo\LanBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base data fixture.
 */
abstract class DataFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * Container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Faker.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __call($method, $arguments)
    {
        $matches = array();
        if (preg_match('/^get(.*)Repository$/', $method, $matches)) {
            return $this->get('sylius.repository.'.$matches[1]);
        }

        return call_user_func_array(array($this, $method), $arguments);
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }
}
