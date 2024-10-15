<?php
class Controller_game extends Controller
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
        // Exemple de tickets utilisateur et gagnant
        $userTicket = "12-3-25-18-7 | 9-2"; // Exemple de ticket utilisateur
        $winningTicket = "5-15-9-30-1 | 8-3"; // Exemple de ticket gagnant
        
        // Trier les tickets
        $sortedUserTicket = $this->sortTicket($userTicket);
        $sortedWinningTicket = $this->sortTicket($winningTicket);
        
        // Affichage des tickets triés pour vérification
        var_dump($userTicket);
        var_dump($sortedUserTicket); // "25-18-12-7-3 | 9-2"
        var_dump($winningTicket);
        var_dump($sortedWinningTicket); // "30-15-9-5-1 | 8-3"
    }
}
?>