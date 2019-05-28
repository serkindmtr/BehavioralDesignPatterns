<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/%D0%A6%D0%B5%D0%BF%D0%BE%D1%87%D0%BA%D0%B0_%D0%BE%D0%B1%D1%8F%D0%B7%D0%B0%D0%BD%D0%BD%D0%BE%D1%81%D1%82%D0%B5%D0%B9
 */

namespace ChainOfResponsibility {

    abstract class Logger {

        const ERR = 3;
        const NOTICE = 5;
        const DEBUG = 7;

        protected $mask;
        // Следующий элемент в цепочке обязанностей
        protected $next;

        public function __construct($mask) {
            $this->mask = $mask;
        }

        public function setNext(Logger $log) {
            $this->next = $log;
            return $log;
        }

        public function message($msg, $priority) {
            if ($priority <= $this->mask) {
                $this->writeMessage($msg);
            }

            if ($this->next != null) {
                $this->next->message($msg, $priority);
            }
        }

        protected abstract function writeMessage($msg);
    }

    class StdoutLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Writing to stdout:%s\n", $msg);
        }

    }

    class EmailLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Sending via email:%s\n", $msg);
        }

    }

    class StderrLogger extends Logger {

        protected function writeMessage($msg) {
            echo sprintf("Sending to stderr:%s\n", $msg);
        }

    }

    //цепочка обязанностей
    class ChainOfResponsibilityExample {
        public function run() {

            // строим цепочку обязанностей
            $logger = new StdoutLogger(Logger::DEBUG);
            $logger1 = $logger->setNext(new EmailLogger(Logger::NOTICE));
            $logger2 = $logger1->setNext(new StderrLogger(Logger::ERR));

            // Handled by StdoutLogger
            $logger->message("Entering function y.", Logger::DEBUG);

            // Handled by StdoutLogger and EmailLogger
            $logger->message("Step1 completed.", Logger::NOTICE);

            // Handled by all three loggers
            $logger->message("An error has occurred.", Logger::ERR);
        }
    }

    $chain = new ChainOfResponsibilityExample();
    $chain->run();

}
