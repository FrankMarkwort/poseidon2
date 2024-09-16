<?php
declare(strict_types=1);
namespace Core\Config;

class PngFieldConfig
{
    private ConfigPgn|null $configInstance = null;
    private array $jsonArray = [];
    private int $order;
    private int $png;
    private const string BIT_LENGTH_VARIABLE = 'BitLengthVariable';

    private const string BIT_LENGTH = 'BitLength';
    private const string BIT_OFFSET = 'BitOffset';
    private const string BIT_START = 'BitStart';
    private const string RANGE_MAX = 'RangeMax';
    private const string RANGE_MIN = 'RangeMin';
    private const string ORDER = 'Order';
    private const string SIGNED = 'Signed';
    private const string RESOLUTION = 'Resolution';
    private const string UNITS ='Units';
    //const string FIELDS = 'Fields';
    const string TYPE = 'Type';
    const string ENUM_VALUES = 'EnumValues';

    const string NAME = 'Name';

    public function setConfigInstance(ConfigPgn $configInstance): self
    {
        $this->configInstance = $configInstance;

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function setPgn(int $png):self
    {
        $this->png = $png;
        $this->jsonArray = $this->configInstance->getFields($this->png);

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function getDescription():string
    {
        return $this->configInstance->getDescription($this->png);
    }

    /**
     * @throws ConfigException
     */
    public function getOrderIds():array
    {
        return $this->configInstance->getOrderIds($this->png);
    }

    private function getFieldsJson(): array
    {
        return $this->jsonArray;
    }

    /**
     * @throws ConfigException
     */
    public function getFieldsByOrder(int $order): array
    {
        $this->order = $order;
        $results = array_filter($this->getFieldsJson(), function($pgn) {

            return $pgn[static::ORDER] == $this->order;
        });

        $results = current($results);
        if (!$results) {

            throw new ConfigException('Config Field not found');
        }

        return $results;
    }

    public function count():int
    {
        return count($this->getFieldsJson());
    }

    /**
     * @throws ConfigException
     */
    public function getBitLengthVariable($order):bool
    {
        if (isset($this->getFieldsByOrder($order)[static::BIT_LENGTH_VARIABLE])) {

            return $this->getFieldsByOrder($order)[static::BIT_LENGTH_VARIABLE];
        }
        return false;
    }

    /**
     * @throws ConfigException
     */
    public function getBitLength(int $order):int
    {
        return $this->getFieldsByOrder($order)[static::BIT_LENGTH];
    }

    /**
     * @throws ConfigException
     */
    public function getBitOffset(int $order):int
    {
        return  $this->getFieldsByOrder($order)[static::BIT_OFFSET];
    }

    /**
     * @throws ConfigException
     */
    public function getBitStart(int $order):int
    {
        return $this->getFieldsByOrder($order)[static::BIT_START];
    }

    /**
     * @throws ConfigException
     */
    public function getResolution(int $order): ?float
    {
        return isset($this->getFieldsByOrder($order)[static::RESOLUTION])? $this->getFieldsByOrder($order)[static::RESOLUTION]: null;
    }

    /**
     * @throws ConfigException
     */
    public function getUnits(int $order): ?string
    {
        return isset($this->getFieldsByOrder($order)[static::UNITS])? $this->getFieldsByOrder($order)[static::UNITS]: null;
    }

    /**
     * @throws ConfigException
     */
    public function getSigned(int $order):bool
    {
        return (bool) $this->getFieldsByOrder($order)[static::SIGNED];
    }

    /**
     * @throws ConfigException
     */
    public function getType(int $order): ?string
    {
        return isset($this->getFieldsByOrder($order)[static::TYPE])? $this->getFieldsByOrder($order)[static::TYPE]: null;
    }

    /**
     * @throws ConfigException
     */
    public function getRangeMin(int $order): int|float|null
    {
        return isset($this->getFieldsByOrder($order)[static::RANGE_MIN])? $this->getFieldsByOrder($order)[static::RANGE_MIN]: null;
    }

    /**
     * @throws ConfigException
     */
    public function getRangeMax(int $order): int|float|null
    {
        return isset($this->getFieldsByOrder($order)[static::RANGE_MAX])? $this->getFieldsByOrder($order)[static::RANGE_MAX]: null;
    }

    /**
     * @throws ConfigException
     */
    public function getEnumValues(int $order): ?array
    {
        return isset($this->getFieldsByOrder($order)[static::ENUM_VALUES]) && is_array($this->getFieldsByOrder($order)[static::ENUM_VALUES])?
            $this->getFieldsByOrder($order)[static::ENUM_VALUES] :[];
    }

    /**
     * @throws ConfigException
     */
    public function getName(int $order):string
    {
       return $this->getFieldsByOrder($order)[static::NAME];
    }
}
