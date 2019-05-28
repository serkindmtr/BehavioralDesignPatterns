<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/%D0%A1%D0%BE%D1%81%D1%82%D0%BE%D1%8F%D0%BD%D0%B8%D0%B5_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

/**
 * Паттерн Состояние управляет изменением поведения объекта при изменении его внутреннего состояния.
 * Внешне это выглядит так, словно объект меняет свой класс.
 */

namespace state1 {


    class Client
    {
        public function __construct()
        {
            $context = new Context();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
            $context->request();
        }
    }

    class Test
    {
        public static function go()
        {
            $client = new Client();
        }
    }

    /**
     *  Класс с несколькими внутренними состояниями
     */
    class Context
    {
        /**
         * @var AState
         */
        public $state;

        const STATE_A = 1;
        const STATE_B = 2;
        const STATE_C = 3;

        public function __construct()
        {
            $this->setState(Context::STATE_A);
        }

        /**
         * Действия Context делегируются объектам состояний для обработки
         */
        public function request()
        {
            $this->state->handle();
        }

        /**
         * Это один из способов реализации переключения состояний
         * @param $state выбранное состояние, возможные варианты перечислены в списке констант Context::STATE_..
         */
        public function setState($state)
        {
            if ($state == Context::STATE_A) {
                $this->state = new ConcreteStateA($this);
            } elseif ($state == Context::STATE_B) {
                $this->state = new ConcreteStateB($this);
            } elseif ($state == Context::STATE_C) {
                $this->state = new ConcreteStateC($this);
            }
        }

    }

    /**
     * Общий интерфейс всех конкретных состояний.
     * Все состояния реализуют один интерфейс, а следовтельно, являются взаимозаменяемыми.
     */
    class AState
    {
        /**
         * @var Context храним ссылку на контекст для удобного переключения состояний
         */
        protected $context;

        public function __construct($context)
        {
            $this->context = $context;
        }

        /**
         * Обработка в разных состояниях может отличаться.
         * Если AState не просто интерфейс а абстрактный класс,
         * то он может содержать стандартные обработки, тогда классы конкретных состояний будут описывать только свои особенности относительно стандартного поведения.
         */
        public function handle()
        {
            echo "\n standart handle";
        }

    }

    /**
     * Далее идёт набор конкретных состояний, которые обрабатывают запросы от Context.
     * Каждый класс предоставляет собственную реализацию запроса.
     * Таким образом, при переходе объекта Context в другое состояние, меняется и его повденеие.
     */

    class ConcreteStateA extends AState
    {
        public function handle()
        {
            echo "\n State A handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_B);
        }

    }

    class ConcreteStateB extends AState
    {
        public function handle()
        {
            echo "\n State B handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_C);
        }
    }

    class ConcreteStateC extends AState
    {
        public function handle()
        {
            echo "\n State C handle";
            // переключаем состояние
            $this->context->setState(Context::STATE_A);
        }
    }


    Test::go();
}