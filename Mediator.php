<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/%D0%9F%D0%BE%D1%81%D1%80%D0%B5%D0%B4%D0%BD%D0%B8%D0%BA_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

abstract class Mediator
{
    abstract public function send($message, Colleague $colleague);
}

abstract class Colleague
{
    protected $mediator;

    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    public function send($message)
    {
        $this->mediator->send($message, $this);
    }

    /**
     * Обработка полученного сообщения реализуется каждым конкретным
     * наследником
     * @param string message получаемое сообщение
     */
    abstract public function notify($message);
}

class ConcreteMediator extends Mediator
{
    /**
     * @var ConcreteColleague1
     */
    private $colleague1;

    /**
     * @var ConcreteColleague2
     */
    private $colleague2;

    public function setColleague1(ConcreteColleague1 $colleague)
    {
        $this->colleague1 = $colleague;
    }

    public function setColleague2(ConcreteColleague2 $colleague)
    {
        $this->colleague2 = $colleague;
    }

    public function send($message, Colleague $colleague)
    {
        switch ($colleague) {
            case $this->colleague1:
                $this->colleague2->notify($message);
                break;
            case $this->colleague2:
                $this->colleague1->notify($message);
        }
    }
}


//коллега 1
class ConcreteColleague1 extends Colleague
{
    public function notify($message)
    {
        echo sprintf("Colleague1 gets message: %s\n", $message);
    }
}

//коллега 2
class ConcreteColleague2 extends Colleague
{
    public function notify($message)
    {
        echo sprintf("Colleague2 gets message: %s\n", $message);
    }
}


$mediator = new ConcreteMediator();

$collegue1 = new ConcreteColleague1($mediator);
$collegue2 = new ConcreteColleague2($mediator);

$mediator->setColleague1($collegue1);
$mediator->setColleague2($collegue2);

$collegue1->send('How are you ?');
$collegue2->send('Fine, thanks!');