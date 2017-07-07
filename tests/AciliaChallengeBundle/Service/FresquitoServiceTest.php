<?php

use PHPUnit\Framework\TestCase;

class FresquitoServiceMock extends \AciliaChallengeBundle\Service\FresquitoService
{
    public function readJSON()
    {
        return FresquitoServiceTest::getMeasurements()[0][0];
    }
}

class FresquitoServiceTest extends TestCase
{

    public static function getMeasurements()
    {

        return
        [
          [
            [
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Teruel',
                'tmed' => "12,3"
              ],
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Madrid',
                'tmed' => "21,3"
              ],
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Alpedrete',
                'tmed' => "2,3"
              ],
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Gijon',
                'tmed' => "18,3"
              ],
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Gandia',
                'tmed' => "13,3"
              ],
              [
                'date' => '2017-06-22',
                'no_used' => 1,
                'nombre' => 'Sagunto',
                'tmed' => "17,3"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Teruel',
                'tmed' => "12,9"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Madrid',
                'tmed' => "21,9"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Alpedrete',
                'tmed' => "2,9"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Gijon',
                'tmed' => "18,9"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Gandia',
                'tmed' => "13,9"
              ],
              [
                'date' => '2017-06-23',
                'no_used' => 1,
                'nombre' => 'Sagunto',
                'tmed' => "17,9"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Teruel',
                'tmed' => "12,1"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Madrid',
                'tmed' => "25,1"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Alpedrete',
                'tmed' => "5,1"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Gijon',
                'tmed' => "13,1"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Gandia',
                'tmed' => "12,1"
              ],
              [
                'date' => '2017-06-24',
                'no_used' => 1,
                'nombre' => 'Sagunto',
                'tmed' => "22,1"
              ]
            ]
          ]
        ];
    }

    /**
     * @dataProvider getMeasurements
     */
    public function testParseJson($results)
    {
        $fresquito = new \AciliaChallengeBundle\Service\FresquitoService();

        $cities = $fresquito->parseResults($results);

        $this->assertCount(count($results) / 3, $cities);
    }

    /**
     * @dataProvider getMeasurements
     */
    public function testCalculateAverages($results)
    {
        $fresquito = new \AciliaChallengeBundle\Service\FresquitoService();

        $cities = $fresquito->calculateAverages($fresquito->parseResults($results));

        $this->assertEquals(number_format($cities['Teruel'], 2), 12.43);
    }

    /**
     * @dataProvider getMeasurements
     */
    public function testSortByCold($results)
    {
        $fresquito = new \AciliaChallengeBundle\Service\FresquitoService();

        $cities = $fresquito->sortByCold($fresquito->calculateAverages($fresquito->parseResults($results)));

        $this->assertEquals(key($cities), 'Alpedrete');
    }

    public function testgetResults()
    {
        $fresquito = new FresquitoServiceMock();

        $cities = $fresquito->getResults();

        $this->assertCount(3, $cities);
        $this->assertEquals($cities[0][0], 0);
        $this->assertEquals($cities[0][1], 'Alpedrete');
        $this->assertEquals(number_format($cities[0][2], 2), 3.43);
    }
}