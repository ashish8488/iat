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
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new IATBundle\IATBundle(),
        );
        
        $bundles = array_merge($bundles, $this->loadExternalVendorBundle());

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }
    
    /**
     * below method is used to manage and load external Bundles only.
     * we will only write here the extenrnal vendor bundle which we require only.
     */
    public function loadExternalVendorBundle() {
    	return array(
    			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
    			new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
    			new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
    			//new FOS\UserBundle\FOSUserBundle(),
    			new Liuggio\ExcelBundle\LiuggioExcelBundle(),
    	);
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
