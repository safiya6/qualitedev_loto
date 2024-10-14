<?php
session_start();

class Controller_partie extends Controller
{
    public function action_default()
    {
        $this->render("simulation");
    }

    public function action_selectRandomJoueurs()
    {
        $model = Model::getModel();
        $nombre = isset($_POST['nombre']) ? (int)$_POST['nombre'] : 10;
        $nombre = max(1, min($nombre, 100));

        $joueurs = $model->selectRandomJoueurs_pred($nombre);

        foreach ($joueurs as $joueur) {
            $model->insertJoueurEnCoursPred($joueur['id_joueur']);
        }

        $joueursEnCours = $model->selectJoueursEnCoursPred();

        // Vérifier si la requête est AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            ob_start();
            foreach ($joueursEnCours as $joueur) {
                echo "<div class='data-row'>
                        <div class='user-item'>" . htmlspecialchars($joueur['pseudo']) . "</div>
                        <div class='ticket-item'>" . htmlspecialchars($joueur['ticket']) . "</div>
                        <button type='button' class='edit-button' onclick='showEditForm(" . $joueur['id_joueur'] . ", \"" . htmlspecialchars($joueur['pseudo'], ENT_QUOTES) . "\", \"" . htmlspecialchars($joueur['ticket'], ENT_QUOTES) . "\")'>🖊️ Modifier</button>
                        <button type='button' class='delete-button' onclick='deleteUser(" . $joueur['id_joueur'] . ")'>🗑️ Supprimer</button>
                    </div>";
            }
            $output = ob_get_clean();
            echo json_encode(['success' => true, 'html' => $output]);
            exit;
        } else {
            // Si ce n'est pas une requête AJAX, renvoyer toute la vue
            $this->render("simulation", ['joueurs' => $joueursEnCours]);
        }
    }

    public function action_deleteUser()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET['id_joueur'])) {
            $id_joueur = intval($_GET['id_joueur']);
            $model = Model::getModel();
            $model->deleteJoueurs_en_cours($id_joueur);

            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }

    public function action_editUser()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_joueur'], $_POST['pseudo'], $_POST['numbers'], $_POST['stars'])) {
            $id_joueur = (int)$_POST['id_joueur'];
            $pseudo = trim($_POST['pseudo']);
            $numbers = explode(",", trim($_POST['numbers']));
            $stars = explode(",", trim($_POST['stars']));

            if (count($numbers) === 5 && count($stars) === 2) {
                sort($numbers);
                sort($stars);
                $ticket = implode("-", $numbers) . " | " . implode("-", $stars);

                $model = Model::getModel();
                $success = $model->updateJoueurPred($id_joueur, $pseudo, $ticket);

                echo json_encode(['success' => $success, 'message' => $success ? "Modification réussie" : "Erreur : pseudo ou ticket déjà existant"]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Données non spécifiées ou invalides.']);
        exit;
    }
}
