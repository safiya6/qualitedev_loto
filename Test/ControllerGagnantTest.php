<?php

use PHPUnit\Framework\TestCase;
use App\Controller_gagnant;

class ControllerGagnantTest extends TestCase
{
    public function testCalculateScoreWithEqualNumbersAndStars()
    {
        $controller = new Controller_gagnant();
        $userTicket = "5-15-9-30-1 | 8-3";
        $winningTicket = "5-15-9-30-1 | 8-3";

        $result = $controller->calculateScore($userTicket, $winningTicket);

        $this->assertEquals(5, $result['numero_egalite']);
        $this->assertEquals(2, $result['etoile_egalite']);
        $this->assertEquals(0, $result['ecart']);
    }

    public function testCalculateScoreWithPartialMatch()
    {
        $controller = new Controller_gagnant();
        $userTicket = "5-15-8-25-1 | 8-3";
        $winningTicket = "5-15-9-30-1 | 8-2";

        $result = $controller->calculateScore($userTicket, $winningTicket);

        $this->assertEquals(3, $result['numero_egalite']);
        $this->assertEquals(1, $result['etoile_egalite']);
        $this->assertGreaterThan(0, $result['ecart']);
    }
}
