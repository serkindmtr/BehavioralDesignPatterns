<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/%D0%A1%D1%82%D1%80%D0%B0%D1%82%D0%B5%D0%B3%D0%B8%D1%8F_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

interface NamingStrategy
{
    function createName($filename);
}
 
class ZipFileNamingStrategy implements NamingStrategy
{
    function createName($filename)
    {
        return "http://downloads.foo.bar/{$filename}.zip";
    }
}
 
class TarGzFileNamingStrategy implements NamingStrategy
{
    function createName($filename)
    {
        return "http://downloads.foo.bar/{$filename}.tar.gz";
    }
}

class Context
{
    private $namingStrategy;
    function __construct(NamingStrategy $strategy)
    {
        $this->namingStrategy = $strategy;
    }
    function execute()
    {
        $url[] = $this->namingStrategy->createName("Calc101");
        $url[] = $this->namingStrategy->createName("Stat2000");

        return $url;
    }
}

if (strstr($_SERVER["HTTP_USER_AGENT"], "Win"))
    $context = new Context(new ZipFileNamingStrategy());
else
    $context = new Context(new TarGzFileNamingStrategy());

$context->execute();
?>
