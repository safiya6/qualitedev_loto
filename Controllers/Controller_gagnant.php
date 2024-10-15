<?php
//namespace App\Controllers;

class Controller_gagnant extends Controller
{
    /**
     * Fonction qui trie les numéros et les étoiles d'un ticket dans l'ordre décroissant
     * et le recompose sous forme de chaîne.
     */

    public function action_default()
    {
        // Initialisation du modèle
        $model = Model::getModel();
        
        // Vider et remplir la session avec les joueurs en cours (si nécessaire)
        $_SESSION['currentPlayers'] = [];  // Vider
        $joueursEnCours = $model->selectJoueursEnCoursPred();
        foreach ($joueursEnCours as $joueur) {
            $_SESSION['currentPlayers'][$joueur['id_joueur']] = $joueur;
        }

        // Appeler la fonction pour calculer les scores
        $this->action_calculateScores();
        $topWinners = $this->action_getTopWinners();
        $this->action_distributeGains(3000000);
        var_dump($_SESSION['topWinners']);
    }

    private function action_sortTicket($ticket)
    {
        // Séparer les numéros et les étoiles
        list($numbers, $stars) = explode(" | ", $ticket);
        
        // Convertir en entiers, puis trier en ordre décroissant
        $numbers = explode("-", $numbers);
        $stars = explode("-", $stars);
        
        $numbers = array_map('intval', $numbers);
        $stars = array_map('intval', $stars);
        
        rsort($numbers);
        rsort($stars);

        // Recomposer le ticket trié
        $sortedTicket = implode("-", $numbers) . " | " . implode("-", $stars);
        
        return $sortedTicket;
    }

    public function action_calculateScores()
    {
        // Ticket gagnant (exemple). Vous pouvez le définir dynamiquement ou l'importer depuis une source.
        $winningTicket = "5-15-9-30-1 | 8-3"; 
    
        // Vérifiez si la session des joueurs en cours existe
        if (isset($_SESSION['currentPlayers'])) {
            foreach ($_SESSION['currentPlayers'] as $id_joueur => $joueur) {
                // Calcul du score pour chaque joueur en fonction de son ticket
                $scoreData = $this->action_calculateScore($joueur['ticket'], $winningTicket);
    
                // Mise à jour des scores dans la session pour chaque joueur
                $_SESSION['currentPlayers'][$id_joueur]['numero_egalite'] = $scoreData['numero_egalite'];
                $_SESSION['currentPlayers'][$id_joueur]['etoile_egalite'] = $scoreData['etoile_egalite'];
                $_SESSION['currentPlayers'][$id_joueur]['ecart'] = $scoreData['ecart'];
            }
        } else {
            echo "Aucun joueur en cours.";
        }
    }
    
    private function action_calculateScore($userTicket, $winningTicket)
    {
        // Séparer les numéros et les étoiles des deux tickets
        list($userNumbers, $userStars) = explode(" | ", $userTicket);
        list($winningNumbers, $winningStars) = explode(" | ", $winningTicket);
    
        // Convertir les tickets en tableaux d'entiers
        $userNumbers = array_map('intval', explode("-", $userNumbers));
        $userStars = array_map('intval', explode("-", $userStars));
        $winningNumbers = array_map('intval', explode("-", $winningNumbers));
        $winningStars = array_map('intval', explode("-", $winningStars));
    
        // Calcul des égalités et de l'écart
        $numero_egalite = count(array_intersect($userNumbers, $winningNumbers));
        $etoile_egalite = count(array_intersect($userStars, $winningStars));
    
        // Calcul de l'écart total entre les numéros
        $ecart = 0;
        foreach ($userNumbers as $num) {
            if (!in_array($num, $winningNumbers)) {
                $closestDiff = min(array_map(function ($winningNum) use ($num) {
                    return abs($num - $winningNum);
                }, $winningNumbers));
                $ecart += $closestDiff;
            }
        }
    
        return [
            'numero_egalite' => $numero_egalite,
            'etoile_egalite' => $etoile_egalite,
            'ecart' => $ecart
        ];
    }

    public function action_getTopWinners()
    {
        // Vérifier que la session contient les joueurs
        if (!isset($_SESSION['currentPlayers']) || empty($_SESSION['currentPlayers'])) {
            echo "Aucun joueur en cours.";
            return [];
        }

        // Récupérer les joueurs de la session
        $players = $_SESSION['currentPlayers'];

        // Trier les joueurs en fonction des critères définis
        usort($players, function($a, $b) {
            // Comparer d'abord par le nombre de numéros en commun (décroissant)
            if ($a['numero_egalite'] !== $b['numero_egalite']) {
                return $b['numero_egalite'] - $a['numero_egalite'];
            }
            
            // Ensuite, par le nombre d'étoiles en commun (décroissant)
            if ($a['etoile_egalite'] !== $b['etoile_egalite']) {
                return $b['etoile_egalite'] - $a['etoile_egalite'];
            }
            
            // Ensuite, par l'écart total (croissant)
            if ($a['ecart'] !== $b['ecart']) {
                return $a['ecart'] - $b['ecart'];
            }

            // En cas d'égalité parfaite, retourner 0 (ex æquo)
            return 0;
        });

        // Limiter à 10 gagnants maximum
        $_SESSION['topWinners']=array_slice($players, 0, min(10, count($players)));
        return array_slice($players, 0, min(10, count($players)));
    }

    public function action_distributeGains($totalGains)
    {
        // Récupérer les gagnants de la session
        if (!isset($_SESSION['topWinners']) || empty($_SESSION['topWinners'])) {
            echo "Aucun gagnant à distribuer.";
            return;
        }

        $topWinners = $_SESSION['topWinners'];
        $numWinners = count($topWinners);
        if ($numWinners == 0) {
            echo "Aucun joueur n'a participé.";
            return;
        }

        // Pourcentages initiaux de gains pour les 10 places
        $initialPercentages = [40, 20, 12, 7, 6, 5, 4, 3, 2, 1];
        $gainPercentages = array_slice($initialPercentages, 0, $numWinners);

        // Ajuster les pourcentages si moins de 10 joueurs pour que le total soit toujours 100%
        $totalPercentage = array_sum($gainPercentages);
        $multiplier = 100 / $totalPercentage;
        $gainPercentages = array_map(function ($percentage) use ($multiplier) {
            return $percentage * $multiplier;
        }, $gainPercentages);

        // Répartir les gains
        $i = 0;
        while ($i < count($topWinners)) {
            $equalCount = 1;
            $cumulativePercentage = $gainPercentages[$i];

            // Trouver le nombre de joueurs à égalité
            while ($i + $equalCount < count($topWinners) &&
                $topWinners[$i]['numero_egalite'] === $topWinners[$i + $equalCount]['numero_egalite'] &&
                $topWinners[$i]['etoile_egalite'] === $topWinners[$i + $equalCount]['etoile_egalite'] &&
                $topWinners[$i]['ecart'] === $topWinners[$i + $equalCount]['ecart']) {
                $cumulativePercentage += $gainPercentages[$i + $equalCount];
                $equalCount++;
            }

            // Calculer les gains équitablement répartis pour les joueurs à égalité
            $gainPerPlayer = ($totalGains * ($cumulativePercentage / 100)) / $equalCount;
            for ($j = 0; $j < $equalCount; $j++) {
                $topWinners[$i + $j]['gain'] = $gainPerPlayer;
            }

            // Passer au prochain groupe de gagnants
            $i += $equalCount;
        }

        // Stocker dans la session
        $_SESSION['topWinners'] = $topWinners;
    }
}
?>