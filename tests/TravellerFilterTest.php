<?php

/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 3/27/2018
 * Time: 9:43 PM
 */
class TravellerFilterTest extends \TestCase
{

    /**
     * @param $current
     * @param $total
     * @param $expected
     * @dataProvider tattoosDataProvider
     */
    public function testGetTattoos($current, $total, $expected)
    {
        $filter = new \App\Filters\TravelerFilter();
        $result = $filter->getTattoos($current, $total);
        $this->assertEquals($expected, $result);

    }

    /**
     *
     * @return array
     */
    public function tattoosDataProvider()
    {
        return [
            array(0, 1, [1]),
            array(1, 4, [2, 3, 4,5]),
            array(1, 1, [2]),
            array(2, 4, [3, 4, 5, 6]),
            array(2, 5, [3, 4, 5, 6, 7]),
        ];
    }
}