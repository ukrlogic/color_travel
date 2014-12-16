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
        $menu->addChild('Авиабилеты', ['route' => 'avia_tickets']);

        $subMenu = $menu->addChild('Услуги', ['uri' => '#']);

        $subMenu->addChild('VIP-услуги', ['route' => 'page', 'routeParameters' => ['name' => 'vip-uslugi']]);
//        $subMenu->addChild('Корп. мероприятия', ['route' => 'page', 'routeParameters' => ['name' => 'mice']]);
        $subMenu->addChild('Оплата туров', ['route' => 'page', 'routeParameters' => ['name' => 'oplata-turov']]);
        $subMenu->addChild('Визовая поддержка', ['route' => 'page', 'routeParameters' => ['name' => 'viza']]);
        $subMenu->addChild('Подарочные сертификаты', ['route' => 'page', 'routeParameters' => ['name' => 'podarochnyie-sertifikatyi']]);
        $subMenu->addChild('Телефонные карты', ['route' => 'page', 'routeParameters' => ['name' => 'telefonnyie-kartyi-travel-sim']]);
//        $subMenu->addChild('Страховки', ['route' => 'page', 'routeParameters' => ['name' => 'zagranpasporta']]);
        $subMenu->addChild('Загранпаспорта', ['route' => 'page', 'routeParameters' => ['name' => 'zagranpasporta']]);
//        $subMenu->addChild('Рассрочка на туры', ['route' => 'page', 'routeParameters' => ['name' => 'mice']]);

        $menu->addChild('Индивидуальный тур', ['uri' => '#']);
        $menu->addChild('О нас', ['route' => 'about']);
        $menu->addChild('MICE', ['route' => 'page', 'routeParameters' => ['name' => 'mice']]);

        if (is_object($securityContext->getToken()->getUser())) {
            $menu->addChild('Личный кабинет', ['route' => 'profile']);
        } else {
            $menu->addChild('Вход / регистрация', ['attributes' => ['id' => 'signup'], 'uri' => '#']);
        }

        return $menu;
    }

    public function profileMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Новости', ['route' => 'profile']);
        $menu->addChild('Настройки', ['route' => 'profile_edit']);
        $menu->addChild('История посещений', ['route' => 'profile_history']);
        $menu->addChild('Избранные туры', ['route' => 'profile_favorites']);

        return $menu;
    }
} 