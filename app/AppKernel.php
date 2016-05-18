<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
            new Catchamonkey\Bundle\ConsoleLoggerBundle\CatchamonkeyConsoleLoggerBundle(),

            new Dizda\Bundle\UserBundle\DizdaUserBundle(),
            new Dizda\Bundle\AppBundle\DizdaAppBundle(),
            new Dizda\Bundle\BlockchainBundle\DizdaBlockchainBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        if (in_array($this->getEnvironment(), array('test'))) {
            // Fix travis hhvm error
            date_default_timezone_set('Europe/Paris');

            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * The the problem of closing connexion during functional tests.
     * https://github.com/doctrine/DoctrineBundle/blob/79bc1e440f8401cc87d33fad45d811ea4ccb8c5d/DoctrineBundle.php#L131
     *
     * Обходим принудительное закрытие коннекта
     *
     * @see https://github.com/doctrine/DoctrineBundle/issues/407
     *
     * {@inheritdoc}
     *
     * @api
     */
    public function shutdown()
    {
        if (false === $this->booted || 'test' !== $this->getEnvironment()) {
            return;
        }

        $this->booted = false;

        foreach ($this->getBundles() as $bundle) {
            /**
             * Вмешиваемся только в один класс и только в тестовой среде окружения
             */
            if ($bundle instanceof Doctrine\Bundle\DoctrineBundle\DoctrineBundle) {
                /**
                 * Используем замыкание для доступа к приватной переменной DoctrineBundle->autoloader
                 * @see http://habrahabr.ru/post/186718/
                 */
                $doctrineShutdown = Closure::bind(
                    function () {
                        if (null !== $this->autoloader) {
                            spl_autoload_unregister($this->autoloader);
                            $this->autoloader = null;
                        }
                    },
                    $bundle,
                    'Doctrine\Bundle\DoctrineBundle\DoctrineBundle'
                );
                $doctrineShutdown();
            } else {
                $bundle->shutdown();
            }

            $bundle->setContainer(null);
        }

        $this->container = null;
    }
}
