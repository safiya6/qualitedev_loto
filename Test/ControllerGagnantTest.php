<?php
namespace App\Test;

use PHPUnit\Framework\TestCase;
use App\Controllers\Controller_gagnant;
use App\Models\Model;

class ControllerGagnantTest extends TestCase
{
    protected $controller;
    protected $model;

    protected function setUp(): void
    {
        $this->controller = new Controller_gagnant();
        $this->model = $this->createMock(Model::class);
        $this->controller->setModel($this->model); // Méthode à créer pour injecter le modèle mocké
        $_SESSION = [];
    }

    public function testSinglePlayerScoreCalculation()
    {
        $_SESSION['currentPlayers'] = [
            1 => ['id_joueur' => 1, 'ticket' => '5-10-15-20-25 | 1-2']
        ];

        $winningTicket = '5-10-15-20-25 | 1-2';
        $this->controller->action_calculateScores($winningTicket);

        $this->assertEquals(5, $_SESSION['currentPlayers'][1]['numero_egalite']);
        $this->assertEquals(2, $_SESSION['currentPlayers'][1]['etoile_egalite']);
        $this->assertEquals(0, $_SESSION['currentPlayers'][1]['ecart']);
    }

    public function testRankingAndGainsDistributionWithMultiplePlayers()
    {
        $_SESSION['currentPlayers'] = [
            1 => ['id_joueur' => 1, 'ticket' => '5-10-15-20-25 | 1-2'],
            2 => ['id_joueur' => 2, 'ticket' => '5-10-15-20-22 | 1-2'],
            3 => ['id_joueur' => 3, 'ticket' => '4-9-14-19-24 | 1-2'],
            4 => ['id_joueur' => 4, 'ticket' => '3-8-13-18-23 | 1-2'],
            5 => ['id_joueur' => 5, 'ticket' => '2-7-12-17-22 | 1-2']
        ];

        $winningTicket = '5-10-15-20-25 | 1-2';
        $this->controller->action_calculateScores($winningTicket);

        $topWinners = $this->controller->action_getTopWinners();
        
        $expectedOrder = [1, 2, 3, 4, 5];
        foreach ($topWinners as $index => $winner) {
            $this->assertEquals($expectedOrder[$index], $winner['id_joueur']);
        }

        $this->controller->action_distributeGains(3000000);
        $expectedGains = [1200000, 600000, 360000, 210000, 180000];
        foreach ($_SESSION['topWinners'] as $index => $winner) {
            $this->assertEquals($expectedGains[$index], $winner['gain']);
        }
    }

    public function testRankingAndGainsWithEqualScores()
    {
        $_SESSION['currentPlayers'] = [
            1 => ['id_joueur' => 1, 'ticket' => '5-10-15-20-25 | 1-2'],
            2 => ['id_joueur' => 2, 'ticket' => '5-10-15-20-24 | 1-2'],
            3 => ['id_joueur' => 3, 'ticket' => '5-10-15-19-23 | 1-2'],
            4 => ['id_joueur' => 4, 'ticket' => '5-10-15-18-22 | 1-2'],
        ];

        $winningTicket = '5-10-15-20-25 | 1-2';
        $this->controller->action_calculateScores($winningTicket);

        $topWinners = $this->controller->action_getTopWinners();
        
        $expectedOrder = [1, 2, 3, 4];
        foreach ($topWinners as $index => $winner) {
            $this->assertEquals($expectedOrder[$index], $winner['id_joueur']);
            $this->assertEquals(4, $winner['numero_egalite']);
            $this->assertEquals(2, $winner['etoile_egalite']);
        }

        $this->controller->action_distributeGains(3000000);
        $expectedGains = [1200000, 600000, 600000, 600000];
        foreach ($_SESSION['topWinners'] as $index => $winner) {
            $this->assertEquals($expectedGains[$index], $winner['gain']);
        }
    }

    public function testEqualGainsDistributionWithCompleteTie()
    {
        $_SESSION['currentPlayers'] = [
            1 => ['id_joueur' => 1, 'ticket' => '5-10-15-20-25 | 1-2'],
            2 => ['id_joueur' => 2, 'ticket' => '5-10-15-20-25 | 1-2'],
            3 => ['id_joueur' => 3, 'ticket' => '5-10-15-20-25 | 1-2'],
        ];

        $winningTicket = '5-10-15-20-25 | 1-2';
        $this->controller->action_calculateScores($winningTicket);

        $topWinners = $this->controller->action_getTopWinners();
        $this->assertCount(3, $topWinners);

        $this->controller->action_distributeGains(3000000);
        
        foreach ($_SESSION['topWinners'] as $winner) {
            $this->assertEquals(1000000, $winner['gain']);
        }
    }
}
