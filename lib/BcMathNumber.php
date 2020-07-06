<?php

namespace Webit\Wrapper\BcMath;

final class BcMathNumber
{
    /**
     * Operations constants
     */
    private const OPERATION_ADD = 'add';
    private const OPERATION_SUB = 'sub';
    private const OPERATION_MUL = 'mul';
    private const OPERATION_DIV = 'div';
    private const OPERATION_POW = 'pow';
    private const OPERATION_MOD = 'mod';
    private const OPERATION_POWMOD = 'powmod';
    private const OPERATION_SQRT = 'sqrt';
    private const OPERATION_COMPARE = 'compare';

    private static $operationsMap = [
        self::OPERATION_ADD => 'bcadd',
        self::OPERATION_SUB => 'bcsub',
        self::OPERATION_MUL => 'bcmul',
        self::OPERATION_DIV => 'bcdiv',
        self::OPERATION_POW => 'bcpow',
        self::OPERATION_MOD => 'bcmod',
        self::OPERATION_POWMOD => 'bcpowmod',
        self::OPERATION_SQRT => 'bcsqrt',
        self::OPERATION_COMPARE => 'bccomp'
    ];

    /** @var int */
    private static $defaultScale = 8;

    /** @var string */
    private $value;

    /**
     * @param BcMathNumber|string $num
     */
    public function __construct($num = '0')
    {
        $this->value = $num instanceof self ? (string)$num : self::filterNum($num);
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function add($num, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_ADD, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function sub($num, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_SUB, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function mul($num, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_MUL, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function div($num, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_DIV, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return BcMathNumber
     */
    public function pow($num, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_POW, $num, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @param BcMathNumber|string $mod
     * @param int $scale
     * @return BcMathNumber
     */
    public function powmod($num, $mod, ?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_POWMOD, $num, $mod, $scale));
    }

    /**
     * @param int $scale
     * @return BcMathNumber
     */
    public function sqrt(?int $scale = null): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_SQRT, null, null, $scale));
    }

    /**
     * @param BcMathNumber|string $num
     * @return BcMathNumber
     */
    public function mod($num): BcMathNumber
    {
        return self::create($this->performOperation(self::OPERATION_MOD, $num, null));
    }

    /**
     * @param BcMathNumber|string $num
     * @return BcMathNumber
     */
    public static function create($num): BcMathNumber
    {
        return new self($num);
    }

    /**
     * @param BcMathNumber|null $num
     * @param int $scale
     * @return int
     */
    public function compare($num, ?int $scale = null): int
    {
        $result = $this->performOperation(self::OPERATION_COMPARE, $num, null, $scale);

        return (int)$result;
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) == 0;
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isGreaterThan($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) == 1;
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isLessThan($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) == -1;
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isGreaterOrEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) >= 0;
    }

    /**
     * @param BcMathNumber|string $num
     * @param int $scale
     * @return boolean
     */
    public function isLessOrEquals($num, ?int $scale = null): bool
    {
        return $this->compare($num, $scale) <= 0;
    }

    /**
     * @param string $operation
     * @param BcMathNumber|string $num
     * @param int $scale
     * @param BcMathNumber|string $mod
     * @return string
     */
    private function performOperation($operation, $num = null, $mod = null, ?int $scale = null)
    {
        $left = $this->getValue();
        $right = $num instanceof self ? $num->getValue() : self::filterNum($num);
        $mod = $mod instanceof self ? $num->getValue() : self::filterNum($mod);
        $scale = (int)(null === $scale ? self::$defaultScale : $scale);

        switch ($operation) {
            case self::OPERATION_POWMOD:
                $args = [$left, $right, $mod, $scale];
                break;
            case self::OPERATION_SQRT:
                $args = [$left, $scale];
                break;
            case self::OPERATION_MOD:
                $args = [$left, $right];
                break;
            default:
                $args = [$left, $right, $scale];
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
    public function getValue(): string
    {
        return (string)$this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @return float
     */
    public function toFloat(): float
    {
        return (float)$this->value;
    }

    /**
     * @param string $num
     * @return string
     */
    private static function filterNum($num): string
    {
        $num = preg_replace(['/,/', '/[^\-0-9E\.]/i'], ['.', ''], (string)$num);

        return self::convertFromScientificNotation($num);
    }

    /**
     * Converts a number from scientific notation if detected
     *
     * @param string $number
     * @return string
     */
    private static function convertFromScientificNotation(string $number)
    {
        if (!preg_match('/(.*?)E(-?\d+)$/i', $number, $matches)) {
            return $number;
        }

        $base = $matches[1];
        $exponent = $matches[2];

        @list (, $precision) = explode('.', $base);
        $precision = (strlen($precision) + $exponent * -1);
        $precision = $precision >= 0 ? $precision : 0;

        return bcmul($base, bcpow(10, $exponent, abs($exponent)), $precision);
    }

    /**
     * @param int $scale
     */
    public static function setDefaultScale(int $scale)
    {
        self::$defaultScale = $scale;
    }

    /**
     * @return int
     */
    public static function getDefaultScale(): int
    {
        return self::$defaultScale;
    }
}
