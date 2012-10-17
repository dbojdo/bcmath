<?php
namespace Webit\Wrapper\BcMath;

class BcMathNumber {
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
	
	/**
	 * 
	 * @var int
	 */
	static protected $defaultScale = 8;
	
	/**
	 * 
	 * @var int
	 */
	protected $scale;
	
	/**
	 * 
	 * @var string
	 */
	private $value;
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 */
	public function __construct($num = '0', $scale = null) {
		if($num instanceof self) {
			$this->value = (string)$num;
		} else {
			$this->value = $this->filterNum($num);
		}
		
		$this->scale = $this->filterScale($scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function add($num, $scale = null) {
		return $this->performOperation(self::OPERATION_ADD,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function sub($num, $scale = null) {
		return $this->performOperation(self::OPERATION_SUB,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function mul($num, $scale = null) {
		return $this->performOperation(self::OPERATION_MUL,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function div($num, $scale = null) {
		return $this->performOperation(self::OPERATION_DIV,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function pow($num, $scale = null) {
		return $this->performOperation(self::OPERATION_POW,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param BcMathNumber|string $mod
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function powmod($num, $mod, $scale = null) {
		return $this->performOperation(self::OPERATION_POW,$num,$mod,$scale);
	}
	
	/**
	 * 
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function sqrt($scale = null) {
		return $this->performOperation(self::OPERATION_SQRT,null,null,$scale);
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public function mod($num, $scale = null) {
		return $this->performOperation(self::OPERATION_MOD,$num,null,$scale);
	}
	
	/**
	 * 
	 * @param string $num
	 * @param int $scale
	 * @return BcMathNumber
	 */
	public static function create($num) {
		return new self($num);
	}
	
	/**
	 * 
	 * @param BcMathNumber|null $num
	 * @param int $scale
	 * @return int;
	 */
	public function compare($num,$scale = null) {
		$result = $this->performOperation(self::OPERATION_COMPARE, $num, null, $scale);
		 
		return (int)$result->getValue();
	}
	
	/**
	 * 
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return boolean
	 */
	public function isEquals($num,$scale = null) {
		return $this->compare($num,$scale) == 0;
	}
	
	/**
	 *
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return boolean
	 */
	public function isGreaterThan($num,$scale = null) {
		return $this->compare($num,$scale) == 1;
	}
	
	/**
	 *
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return boolean
	 */
	public function isLessThan($num,$scale = null) {
		return $this->compare($num,$scale) == -1;
	}
	
	/**
	 *
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return boolean
	 */
	public function isGreaterOrEquals($num, $scale = null) {
		return $this->compare($num,$scale) >= 0;
	}
	
	/**
	 *
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @return boolean
	 */
	public function isLessOrEquals($num, $scale = null) {
		return $this->compare($num,$scale) <= 0;
	}
	
	/**
	 * 
	 * @param string $operation
	 * @param BcMathNumber|string $num
	 * @param int $scale
	 * @param BcMathNumber|string $mod
	 * @return BcMathNumber
	 */
	private function performOperation($operation,$num = null,$mod = null,$scale) {
		$left = $this->getValue();
		$right = $num instanceof self ? $num->getValue() : $this->filterNum($num);
		$mod = $mod instanceof self ? $num->getValue() : $this->filterNum($mod);
		
		$scale = $this->filterScale($scale);
		
		ob_start();
		switch($operation) {
			case self::OPERATION_ADD:
				echo 'ble';
				$result = bcadd($left,$right,$scale);
			break;
			case self::OPERATION_SUB:
				$result = bcsub($left,$right,$scale);
			break;
			case self::OPERATION_MUL:
				$result = bcmul($left,$right,$scale);
			break;
			case self::OPERATION_DIV:
				$result = bcdiv($left,$right,$scale);
			break;
			case self::OPERATION_POW:
				$result = bcpow($left,$right,$scale);
			break;
			case self::OPERATION_MOD:
				$result = bcmod($left,$right,$scale);
			break;
			case self::OPERATION_POWMOD:
				$mod = $this->filterNum($mod);
				$result = bcmod($left,$right,$mod,$scale);
			break;
			case self::OPERATION_SQRT:
				$result = bcsqrt($left,$scale);
			break;
			case self::OPERATION_COMPARE:
				$result = bccomp($left,$right,$scale);
			break;
		}
		$error = ob_get_flush();
		
		if($error) {
			throw new BcMathException($error);
		}
		
		if(false == isset($result)) {
			throw new BcMathException('Unknown or unsupported bcmath operation: ' . $operation);
		}
		
		return self::create($result);
	}
	
	/**
	 * 
	 * @param int $scale
	 * @return int
	 */
	private function filterScale($scale) {
		if($scale == null) {
			$scale = $this->scale ?: self::$defaultScale;
		}
		
		return (int)$scale;
	}
	
	/**
	 * 
	 * @param string $num
	 * @return string
	 */
	private function filterNum($num) {
		$num = (string)$num;
		$num = preg_replace('/,/', '.', $num);
		$num = preg_replace('/[^\-0-9\.]/','',$num);
		
		return $num;
	}
	
	/**
	 * 
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * 
	 * @param int $scale
	 */
	public function setScale($scale) {
		$this->scale = (int)$scale;
	}
	
	/**
	 * @return int
	 */
	public function getScale() {
		return $this->scale;
	}
	
	/**
	 * 
	 * @param int $scale
	 */
	static public function setDefaultScale($scale) {
		self::$defaultScale = $scale;
	}
	
	/**
	 * @return int
	 */
	static public function getDefaultScale() {
		return self::$defaultScale;
	}
	
	public function __toString() {
		return (string)$this->value . "\n";
	}
}
?>