<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/25/14
 * Time: 1:59 PM
 */
namespace Application\UkrLogic\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\SecurityContext;

class Builder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function mainMenu(SecurityContext $securityContext)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Поиск тура', ['route' => 'tours_search']);
        $menu->addChild('Авиабилеты', ['uri' => '#']);

        $subMenu = $menu->addChild('Услуги', ['uri' => '#']);

        $subMenu->addChild('VIP-услуги', ['uri' => '#']);
        $subMenu->addChild('Корп. мероприятия', ['uri' => '#']);
        $subMenu->addChild('Оплата туров', ['uri' => '#']);
        $subMenu->addChild('Визовая поддержка', ['uri' => '#']);
        $subMenu->addChild('Подарочные сертификаты', ['uri' => '#']);
        $subMenu->addChild('Телефонные карты', ['uri' => '#']);
        $subMenu->addChild('Страховки', ['uri' => '#']);
        $subMenu->addChild('Загранпаспорта', ['uri' => '#']);
        $subMenu->addChild('Рассрочка на туры', ['uri' => '#']);

        $menu->addChild('Индивидуальный тур', ['uri' => '#']);
        $menu->addChild('О нас', ['route' => 'about']);
        $menu->addChild('MICE', ['route' => 'page', 'routeParameters' => ['name' => 'Mice']]);

        if (is_object($securityContext->getToken()->getUser())) {
            $menu->addChild('Личный кабинет', ['uri' => '#']);
        } else {
            $menu->addChild('Вход / регистрация', ['attributes' => ['id' => 'signup'], 'uri' => '#']);
        }

        return $menu;
    }
} 