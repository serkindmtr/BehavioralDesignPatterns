<?php 

/**
 * WIKI: 
 * https://ru.wikipedia.org/wiki/%D0%9A%D0%BE%D0%BC%D0%B0%D0%BD%D0%B4%D0%B0_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

 /**
  * Абстрактый класс "команды"
  * @abstract
  */  
  abstract class Command
  {
    public abstract function Execute();
    public abstract function UnExecute();
  }
 
 /**
  * Класс конкретной "команды"
  */ 
  class CalculatorCommand extends Command
  {
   /**
    * Текущая операция команды
    *
    * @var string
    */
    public $operator;

   /**
    * Текущий операнд
    *
    * @var mixed
    */ 
    public $operand;

   /**
    * Класс, для которого предназначена команда
    *
    * @var object of class Calculator
    */ 
    public $calculator;
 
   /**
    * Конструктор
    * 
    * @param object $calculator
    * @param string $operator
    * @param mixed $operand 
    */
    public function __construct($calculator, $operator, $operand)
    {
      $this->calculator = $calculator;
      $this->operator = $operator;
      $this->operand = $operand;
    }
 
   /**
    * Переопределенная функция parent::Execute()
    */ 
    public function Execute()
    {
      $this->calculator->Operation($this->operator, $this->operand);
    }
 
   /**
    * Переопределенная функция parent::UnExecute()
    */ 
    public function UnExecute()
    {
      $this->calculator->Operation($this->Undo($this->operator), $this->operand);
    }
 
   /**
    * Какое действие нужно отменить?
    *
    * @private
    * @param string $operator
    * @return string
    */
    private function Undo($operator)
    {
      //каждому произведенному действию найти обратное
      switch($operator)
      {
        case '+': $undo = '-'; break;
        case '-': $undo = '+'; break;
        case '*': $undo = '/'; break;
        case '/': $undo = '*'; break;
        default : $undo = ' '; break;
      }
      return $undo;
    }
  }
 
 /**
  * Класс получатель и исполнитель "команд"
  */
  class Calculator
  {
   /**
    * Текущий результат выполнения команд
    *
    * @private
    * @var int
    */ 
    private $curr = 0;
 
    public function Operation($operator,$operand)
    {
      //выбрать оператора для вычисления результата
      switch($operator)
      {
        case '+': $this->curr+=$operand; break;
        case '-': $this->curr-=$operand; break;
        case '*': $this->curr*=$operand; break;
        case '/': $this->curr/=$operand; break;
      }
      print("Текущий результат = $this->curr (после выполнения $operator c $operand)");
    }
  }
 
 /**
  * Класс, вызывающий команды
  */
  class User 
  {
   /**
    * Этот класс будет получать команды на исполнение
    *
    * @private
    * @var object of class Calculator
    */
    private $calculator;

   /**
    * Массив операций
    *
    * @private
    * @var array
    */ 
    private $commands = array();

   /**
    * Текущая команда в массиве операций
    *
    * @private
    * @var int
    */ 
    private $current = 0;
 	
    public function __construct()
    {
        //создать экземпляр класса, который будет исполнять команды
    	$this->calculator = new Calculator();
    }
    
   /**
    * Функция возврата отмененных команд
    *
    * @param int $levels количество возвращаемых операций
    */
    public function Redo($levels)
    {
      print("\n---- Повторить $levels операций ");
 
      // Делаем возврат операций
      for ($i = 0; $i < $levels; $i++)
        if ($this->current < count($this->commands) - 1)
          $this->commands[$this->current++]->Execute();
    }
 
   /**
    * Функция отмены команд
    *
    * @param int $levels количество отменяемых операций
    */
    public function Undo($levels)
    {
      print("\n---- Отменить $levels операций ");
 
      // Делаем отмену операций
      for ($i = 0; $i < $levels; $i++)
        if ($this->current > 0)
          $this->commands[--$this->current]->UnExecute();
    }
 
   /**
    * Функция выполнения команд
    *
    * @param string $operator
    * @param mixed $operand
    */
    public function Compute($operator, $operand)
    {
      // Создаем команду операции и выполняем её
      $command = new CalculatorCommand($this->calculator, $operator, $operand);
      $command->Execute();
 
      // Добавляем операцию к массиву операций и увеличиваем счетчик текущей операции
      $this->commands[]=$command;
      $this->current++;
    }
  }


      $user = new User();

      // Произвольные команды
      $user->Compute('+', 100);
      $user->Compute('-', 50);
      $user->Compute('*', 10);
      $user->Compute('/', 2);
 
      // Отменяем 4 команды
      $user->Undo(4);
 
      // Вернём 3 отменённые команды.
      $user->Redo(3
