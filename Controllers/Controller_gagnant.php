<?php
class Controller_gagnant extends Controller
{
    /**
     * Fonction qui trie les numéros et les étoiles d'un ticket dans l'ordre décroissant.
     */
    private function sortTicket($ticket)
    {
        // Séparer les numéros et les étoiles
        $numbers = explode("-", explode(" | ", $ticket)[0]);
        $stars = explode("-", explode(" | ", $ticket)[1]);

        // Convertir en entiers, puis trier en ordre décroissant
        $numbers = array_map('intval', $numbers);
        $stars = array_map('intval', $stars);
        rsort($numbers);
        rsort($stars);

        // Retourner le ticket trié
        return [
            'numbers' => $numbers,
            'stars' => $stars
        ];
    }

    public function action_default()
    {
        // Exemple d'utilisation
        $userTicket = "12-3-25-18-7 | 9-2"; // Exemple de ticket utilisateur
        $winningTicket = "5-15-9-30-1 | 8-3"; // Exemple de ticket gagnant
        
        // Trier les tickets
        $sortedUserTicket = $this->sortTicket($userTicket);
        $sortedWinningTicket = $this->sortTicket($winningTicket);
        
        // Affichage des tickets triés pour vérification
        var_dump($sortedUserTicket);
        var_dump($sortedWinningTicket);
    }
}
?>