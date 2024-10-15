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
}
?>
<?php
