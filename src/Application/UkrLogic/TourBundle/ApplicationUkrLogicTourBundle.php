<?php

namespace Application\UkrLogic\TourBundle;

use Application\UkrLogic\TourBundle\CompilerPass\ToursRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationUkrLogicTourBundle extends Bundle
{
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new ToursRepository());
    }
}
