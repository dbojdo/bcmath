<?php
namespace Webit\Wrapper\BcMath;

class BcMathNumber
{

    /**
     * Operations constants
     */
    const OPERATION_ADD = 'add';
    const OPERATION_SUB = 'sub';
    const OPERATION_MUL = 'mul';
    const OPERATION_DIV = 'div';
    const OPERATION_POW = 'pow';
    const OPERATION_MOD = 'mod';
    const OPERATION_POWMOD = 'powmod';
    const OPERATION_SQRT = 'sqrt';
    const OPERATION_COMPARE = 'compare';

    private static $operationsMap = array(
        self::OPERATION_ADD => 'bcadd',
        self::OPERATION_SUB => 'bcsub',
        self::OPERATION_MUL => 'bcmul',
        self::OPERATION_DIV => 'bcdiv',
        self::OPERATION_POW => 'bcpow',
        self::OPERATION_MOD => 'bcmod',
        self::OPERATION_POWMOD => 'bcpowmod',
        self::OPERATION_SQRT => 'bcsqrt',
        self::OPERATION_COMPARE => 'bccomp'
    );

    /**
     * @var int
     */
    private static $defaultScale = 8;

    /**
     *
     * @var string
     */
    private $value;

    /**
     *
     * @param BcMathNumber|string $num
     */
    public function __construct($num = '0')
    {
        $this->value = $num instanceof self ? (string) $num : self::filterNum($num);
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function add($num, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_ADD, $num, null, $scale));
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function sub($num, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_SUB, $num, null, $scale));
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function mul($num, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_MUL, $num, null, $scale));
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function div($num, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_DIV, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function pow($num, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_POW, $num, null, $scale));
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param BcMathNumber|string $mod
     * @param int $scale
     * @return BcMathNumber
     */
    public function powmod($num, $mod, $scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_POWMOD, $num, $mod, $scale));
    }

    /**
     *
     * @param int $scale
     * @return BcMathNumber
     */
    public function sqrt($scale = null)
    {
        return self::create($this->performOperation(self::OPERATION_SQRT, null, null, $scale));
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @return BcMathNumber
     */
    public function mod($num)
    {
        return self::create($this->performOperation(self::OPERATION_MOD, $num, null));
    }

    /**
     *
     * @param string $num
     * @return BcMathNumber
     */
    public static function create($num)
    {
        return new self($num);
    }

    /**
     *
     * @param BcMathNumber|null $num
     * @param int $scale
     * @return int;
     */
    public function compare($num, $scale = null)
    {
        $result = $this->performOperation(self::OPERATION_COMPARE, $num, null, $scale);

        return (int) $result;
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isEquals($num, $scale = null)
    {
        return $this->compare($num, $scale) == 0;
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isGreaterThan($num, $scale = null)
    {
        return $this->compare($num, $scale) == 1;
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isLessThan($num, $scale = null)
    {
        return $this->compare($num, $scale) == -1;
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isGreaterOrEquals($num, $scale = null)
    {
        return $this->compare($num, $scale) >= 0;
    }

    /**
     *
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isLessOrEquals($num, $scale = null)
    {
        return $this->compare($num, $scale) <= 0;
    }

    /**
     *
     * @param string $operation
     * @param BcMathNumber|string $num
     * @param int $scale
     * @param BcMathNumber|string $mod
     * @return string
     */
    private function performOperation($operation, $num = null, $mod = null, $scale = null)
    {
        $left = $this->getValue();
        $right = $num instanceof self ? $num->getValue() : self::filterNum($num);
        $mod = $mod instanceof self ? $num->getValue() : self::filterNum($mod);
        $scale = (int) ($scale ?: self::$defaultScale);

        switch($operation) {
            case self::OPERATION_POWMOD:
                $args = array($left, $right, $mod, $scale);
                break;
            case self::OPERATION_SQRT:
                $args = array($left, $scale);
                break;
            case self::OPERATION_MOD:
                $args = array($left, $right);
                break;
            default:
                $args = array($left, $right, $scale);
        }

        $func = self::$operationsMap[$operation];

        ob_start();
        $result = call_user_func_array($func, $args);
        $error = ob_get_flush();

        if ($error) {
            throw new BcMathException($error);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @return float
     */
    public function toFloat()
    {
        return (float) $this->value;
    }

    /**
     *
     * @param string $num
     * @return string
     */
    private static function filterNum($num)
    {
        return preg_replace(array('/,/', '/[^\-0-9\.]/'), array('.', ''), (string) $num);
    }

    /**
     * @param int $scale
     */
    public static function setDefaultScale($scale)
    {
        self::$defaultScale = $scale;
    }

    /**
     * @return int
     */
    public static function getDefaultScale()
    {
        return self::$defaultScale;
    }
}
