<?php
/**
 * @author Frank Markwort
 * @date 13.12.2018
 * @email frank.markwort@gmail.com
 * @project Poseidon
 *
 */
namespace Nmea\Config;

class ConfigPgn
{
    static $configFileName = __DIR__ . "/../config/pgns.json";

    private $pngNumber;
    private $jsonConfig;

    const string ORDER = 'Order';
    const string DESCRIPTION = 'Description';
    const string ID = 'Id';
    const string REPEATING_FIELDS = 'RepeatingFields';
    const string COMPLETE = 'Complete';
    const string LENGTH = 'Length';
    const string PNG = 'PNG';
    const string FIELDS = 'Fields';

    public function __construct()
    {
        $this->readJsonConfig();
    }

    private function readJsonConfig()
    {
        $string = file_get_contents(static::$configFileName);
        $this->jsonConfig = json_decode($string, true);
    }

    /**
     * @throws ConfigException
     */
    public function getPngConfig(int $pngNumber) :array
    {
        $this->pngNumber = $pngNumber;
        $results = array_filter($this->jsonConfig['PGNs'], function($pgn) {

            return $pgn['PGN'] == $this->pngNumber;

        });

        $results = current($results);
        if (!is_array($results)) {

            throw new ConfigException("pgnNumber not Exist in json: ". $pngNumber);
        }

        return $results;
    }

    public function getFields(int $pngNumber):array
    {
        return $this->getPngConfig($pngNumber)[static::FIELDS];
    }

    public function getComplete(int $pngNumber):bool
    {
        return $this->getPngConfig($pngNumber)[static::COMPLETE];
    }

    public function getRepeatingFields(int $pngNumber):int
    {
        return $this->getPngConfig($pngNumber)[static::REPEATING_FIELDS];
    }

    public function countFields(int $pngNumber):int
    {
        return count($this->getPngConfig($pngNumber)[static::FIELDS]);
    }

    public function getId(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::ID];
    }

    public function getDescription(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::DESCRIPTION];
    }

    public function getLength(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::LENGTH];
    }

    public function getPng(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::PNG];
    }

    public function getOrderIds(int $pngNumber):array
    {
        $result = [];
        foreach ($this->getFields($pngNumber) as $field) {
            $result[] = $field[static::ORDER];
        }
        return $result;
    }
}