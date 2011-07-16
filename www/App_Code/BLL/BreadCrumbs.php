<?php

/**
 * BLL_BreadCrumbs class
 * Класс для генерации хлебных крошек
 *
 * @author Frame
 * @version 1.0.1
 * @copyright (c) by VisualDesign
 */
class BLL_BreadCrumbs {

    /**
     * Экземпляр класса
     *
     * @var BLL_BreadCrumbs
     */
    protected static $instance;

    const LinkSpitter = ' » ';

    /**
     * Массив хлебных крошек
     *
     * @var array
     */
    private $breadCrumbs = array();

    /**
     * Добавляет один уровень хлебных крошек
     * в виде array('Title' => 'Url')
     *
     * @param array $breadCrumb данные ссылки
     */
    public function Add($breadCrumb) {
        $this->breadCrumbs = array_merge($this->breadCrumbs, $breadCrumb);
    }

    /**
     * Создает хлебные крошки
     *
     * @return string
     */
    public function Bind() {
        //$result = '<div class="breadcrumbs"><a href="/">Главная</a>';
        $result = '<div class="breadcrumbs">';
        //Debug($this->breadCrumbs);
        $count = count($this->breadCrumbs);

        //Debug($this->breadCrumbs);

        if (1 > $count)
            return;

        $str = "";

        $i = 0;

        foreach ($this->breadCrumbs as $title => $link) {
            $str = $title;
            if ($i != 0) {
                $cursplitter = self::LinkSpitter;
            } else {
                $cursplitter = "";
            };
            if (0 == strlen($link)) {
                $result .= $cursplitter . '<span title="на странице нет модулей">' . $title . "</span>";
            } elseif ($link == "#") {
                $result .= $cursplitter . '<span title="текущая страница">' . $title . "</span>";
            } else {
                $result .= $cursplitter . '<a href="' . $link . '">' . $title . '</a>';
            }
            $i++;
        }

        $result .= '</div>';

        if ($str == "Главная")
            $result = "";

        return $result;
    }

    /**
     * Возвращает экземпляр класса
     *
     * @return BLL_BreadCrumbs
     */
    public static function getInstance() {
        if (null == self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

}

?>