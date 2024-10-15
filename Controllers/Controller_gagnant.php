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
        // Vérifier si la session des joueurs en cours existe
        if (isset($_SESSION['currentPlayers'])) {
            // Parcourir chaque joueur dans la session et trier leur ticket
            foreach ($_SESSION['currentPlayers'] as $id_joueur => $joueur) {
                // Trier le ticket du joueur
                $sortedTicket = $this->sortTicket($joueur['ticket']);
                
                // Mettre à jour le ticket trié dans la session
                $_SESSION['currentPlayers'][$id_joueur]['ticket'] = $sortedTicket;
            }
            
            // Affichage pour vérifier les tickets triés des joueurs
            var_dump($_SESSION['currentPlayers']);
        } else {
            echo "Aucun joueur en cours.";
            var_dump($_SESSION['currentPlayers']);
        }
    }
}
?>
<?php
