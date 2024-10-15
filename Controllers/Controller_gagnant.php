<?php
class Controller_gagnant extends Controller
{
    /**
     * Fonction qui trie les numéros et les étoiles d'un ticket dans l'ordre décroissant
     * et le recompose sous forme de chaîne.
     */
    private function sortTicket($ticket)
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

    public function action_default()
    {
        // Vérifier que la session est initialisée
        session_start();

        if (!empty($_SESSION['currentPlayers'])) {
            foreach ($_SESSION['currentPlayers'] as $id_joueur => $joueur) {
                $sortedTicket = $this->sortTicket($joueur['ticket']);
                $_SESSION['currentPlayers'][$id_joueur]['ticket'] = $sortedTicket;
            }
            
            var_dump($_SESSION['currentPlayers']); // Affichage pour vérifier
        } else {
            echo "Aucun joueur en cours.";
        }
    }

    public function action_calculateScores()
    {
        // Ticket gagnant (exemple). Vous pouvez le définir dynamiquement ou l'importer depuis une source.
        $winningTicket = "5-15-9-30-1 | 8-3"; 
    
        // Vérifiez si la session des joueurs en cours existe
        if (isset($_SESSION['currentPlayers'])) {
            foreach ($_SESSION['currentPlayers'] as $id_joueur => $joueur) {
                // Calcul du score pour chaque joueur en fonction de son ticket
                $scoreData = $this->calculateScore($joueur['ticket'], $winningTicket);
    
                // Mise à jour des scores dans la session pour chaque joueur
                $_SESSION['currentPlayers'][$id_joueur]['numero_egalite'] = $scoreData['numero_egalite'];
                $_SESSION['currentPlayers'][$id_joueur]['etoile_egalite'] = $scoreData['etoile_egalite'];
                $_SESSION['currentPlayers'][$id_joueur]['ecart'] = $scoreData['ecart'];
            }
    
            // Affichage pour vérification des scores dans la session
            var_dump($_SESSION['currentPlayers']);
        } else {
            echo "Aucun joueur en cours.";
        }
    }
    
    // Appel de la fonction calculateScore qui reste inchangée
    private function calculateScore($userTicket, $winningTicket)
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
    
    
}
?>

