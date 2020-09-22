<?php

/**
 * This file is part of the moontoast/math library
 *
 * Copyright 2013-2020 Moontoast, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Moontoast\Math;

class BigNumberImmutable extends AbstractBigNumber
{
    /**
     * @var BigNumber
     */
    protected $bigNumber;

    /**
     * {@inheritdoc}
     */
    public function __construct($bigNumber, $scale = null)
    {
        if ($scale === null && $bigNumber instanceof BigNumberInterface) {
            $scale = $bigNumber->getScale();
        }

        $bigNumber = new BigNumber($bigNumber, $scale);

        if ($scale === 0) {
            $bigNumber->setScale($scale);
            $bigNumber->setValue($bigNumber->getValue());
        }

        $this->bigNumber = $bigNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->bigNumber->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function abs()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->abs();

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function add($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->add($number);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function ceil()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->ceil();

        return new static($newBigNumber, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($number)
    {
        return $this->bigNumber->compareTo($number);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToBase($base)
    {
        return $this->bigNumber->convertToBase($base);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->decrement();

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function divide($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->divide($number);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function floor()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->floor();

        return new static($newBigNumber, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getScale()
    {
        return $this->bigNumber->getScale();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->bigNumber->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function increment()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->increment();

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function mod($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->mod($number);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function multiply($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->multiply($number);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function negate()
    {
        return $this->multiply(-1);
    }

    /**
     * {@inheritdoc}
     */
    public function pow($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->pow($number);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function powMod($pow, $mod)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->powMod($pow, $mod);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function round()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->round();
        $newBigNumber->setScale(0);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function shiftLeft($bits)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->shiftLeft($bits);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function shiftRight($bits)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->shiftRight($bits);

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function sqrt()
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->sqrt();

        return new static($newBigNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function subtract($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->subtract($number);

        return new static($newBigNumber);
    }

    /**
     * Sets the scale of this BigNumber
     *
     * @param int $scale Specifies the default number of digits after the decimal
     *                   place to be used in operations for this BigNumber
     * @return BigNumberImmutable   returns a new instance
     */
    public function withScale($scale)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->setScale($scale);

        return new static($newBigNumber);
    }

    /**
     * Sets the value of this BigNumber to a new value
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     * @return BigNumberImmutable   returns a new instance
     */
    public function withValue($number)
    {
        $newBigNumber = clone $this->bigNumber;
        $newBigNumber->setValue($number);

        return new static($newBigNumber);
    }
}
