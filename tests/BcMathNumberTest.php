<?php

namespace Webit\Wrapper\BcMath;

use PHPUnit\Framework\TestCase;

class BcMathNumberTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        bcscale(BcMathNumber::getDefaultScale());
    }

    /**
     * @dataProvider scientificNotationExamples
     * @test
     */
    public function itSupportsScientificNotation($inputNumber, $expectedRepresentation)
    {
        $this->assertEquals($expectedRepresentation, (string)BcMathNumber::create($inputNumber));
    }

    public function scientificNotationExamples()
    {
        return [
            ['-6.232E-6', '-0.000006232'],
            ['-6.232E6', '-6232000'],
            ['25E2', '2500'],
            ['-0.25E5', '-25000'],
            ['-0.254345466766E-5', '-0.00000254345466766'],
            ['12.322', '12.322'],
            ['-12.322', '-12.322'],
            ['12E0', '12']
        ];
    }

    /**
     * @dataProvider twoFloatsAndScale
     * @param string $number1
     * @param string $number2
     * @param int $scale
     */
    public function testAdd($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcadd($number1, $number2, $bcScale),
            (string)BcMathNumber::create($number1)->add($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     * @param string $number1
     * @param string $number2
     * @param int $scale
     */
    public function testSub($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcsub($number1, $number2, $bcScale),
            (string)BcMathNumber::create($number1)->sub($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     * @param string $number1
     * @param string $number2
     * @param int $scale
     */
    public function testMul($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcmul($number1, $number2, $bcScale),
            (string)BcMathNumber::create($number1)->mul($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     * @param string $number1
     * @param string $number2
     * @param int $scale
     */
    public function testDiv($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcdiv($number1, $number2, $bcScale),
            (string)BcMathNumber::create($number1)->div($number2, $scale)
        );
    }

    /**
     * @dataProvider powData
     * @param string $number1
     * @param string $number2
     * @param string $scale
     */
    public function testPow($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcpow($number1, $number2, $bcScale),
            (string)BcMathNumber::create($number1)->pow($number2, $scale)
        );
    }

    /**
     * @dataProvider modData
     * @param string $number1
     * @param string $number2
     */
    public function testMod($number1, $number2)
    {
        $this->assertSame(
            bcmod($number1, $number2),
            (string)BcMathNumber::create($number1)->mod($number2)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     * @param string $number1
     * @param string $number2
     * @param int $scale
     */
    public function testSqrt($number1, $number2, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcsqrt($number1, $bcScale),
            (string)BcMathNumber::create($number1)->sqrt($scale)
        );
    }

    /**
     * @dataProvider powModData
     * @param string $number1
     * @param int $number2
     * @param int $number3
     * @param int $scale
     */
    public function testPowMod($number1, $number2, $number3, $scale)
    {
        $bcScale = $scale === null ? BcMathNumber::getDefaultScale() : $scale;
        $this->assertSame(
            bcpowmod($number1, $number2, $number3, $bcScale),
            (string)BcMathNumber::create($number1)->powmod($number2, $number3, $scale)
        );
    }

    public function twoFloatsAndScale()
    {
        return array(
            'non-empty scale' => array(
                $this->randomFloat(),
                $this->randomFloat(),
                3
            ),
            'null scale' => array(
                $this->randomFloat(),
                $this->randomFloat(),
                null
            ),
            'scale 0' => array(
                $this->randomFloat(),
                $this->randomFloat(),
                0
            )
        );
    }

    public function modData()
    {
        return array(
            'non-empty scale' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
            ),
            'null scale' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
            ),
            'scale 0' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
            )
        );
    }

    public function powData()
    {
        return array(
            'non-empty scale' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
                3
            ),
            'null scale' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
                null
            ),
            'scale 0' => array(
                $this->randomFloat(),
                mt_rand(1, 100),
                0
            )
        );
    }

    public function powModData()
    {
        return array(
            'non-empty scale' => array(
                mt_rand(1, 100),
                mt_rand(1, 100),
                mt_rand(1, 100),
                3
            ),
            'null scale' => array(
                mt_rand(1, 100),
                mt_rand(1, 100),
                mt_rand(1, 100),
                null
            ),
            'scale 0' => array(
                mt_rand(1, 100),
                mt_rand(1, 100),
                mt_rand(1, 100),
                0
            )
        );
    }

    private function randomFloat()
    {
        return (string)(mt_rand(1, mt_getrandmax()) / 1000);
    }
}
