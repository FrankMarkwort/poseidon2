<?php
declare(strict_types=1);
/**
 * @author Frank Markwort
 * @date 13.12.2018
 * @email frank.markwort@gmail.com
 * @project Poseidon
 *
 */
namespace Core\Config;

class ConfigPgn
{
    static string $configFileName = __DIR__ . "/../config/pgns.json";

    private int $pngNumber;
    private array $jsonConfig;

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

    private function readJsonConfig(): void
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

    /**
     * @throws ConfigException
     */
    public function getFields(int $pngNumber):array
    {
        return $this->getPngConfig($pngNumber)[static::FIELDS];
    }

    /**
     * @throws ConfigException
     */
    public function getComplete(int $pngNumber):bool
    {
        return $this->getPngConfig($pngNumber)[static::COMPLETE];
    }

    /**
     * @throws ConfigException
     */
    public function getRepeatingFields(int $pngNumber):int
    {
        return $this->getPngConfig($pngNumber)[static::REPEATING_FIELDS];
    }

    /**
     * @throws ConfigException
     */
    public function countFields(int $pngNumber):int
    {
        return count($this->getPngConfig($pngNumber)[static::FIELDS]);
    }

    /**
     * @throws ConfigException
     */
    public function getId(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::ID];
    }

    /**
     * @throws ConfigException
     */
    public function getDescription(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::DESCRIPTION];
    }

    /**
     * @throws ConfigException
     */
    public function getLength(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::LENGTH];
    }

    /**
     * @throws ConfigException
     */
    public function getPng(int $pngNumber):string
    {
        return $this->getPngConfig($pngNumber)[static::PNG];
    }

    /**
     * @throws ConfigException
     */
    public function getOrderIds(int $pngNumber):array
    {
        $result = [];
        foreach ($this->getFields($pngNumber) as $field) {
            $result[] = $field[static::ORDER];
        }
        return $result;
    }
}