<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/25/14
 * Time: 1:59 PM
 */
namespace Application\UkrLogic\MainBundle\Menu;

use Knp\Menu\FactoryInterface;

class Builder
{
    public function mainMenu(FactoryInterface $factoryInterface, array $options)
    {
        $menu = $factoryInterface->createItem('root');

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
        $menu->addChild('О нас', ['uri' => '#']);
        $menu->addChild('MICE', ['uri' => '#']);
        $menu->addChild('Вход / регистрация', ['attributes' => ['id' => 'signup'], 'uri' => '#']);

        return $menu;
    }
} 