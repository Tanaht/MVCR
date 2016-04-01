<?php

namespace app\controller;

use app\router\Router;
use app\view\View;
use app\model\DatabaseHelper;
use app\model\Carte;
use app\model\User;
use app\util\AssetsManager;

class Controller
{
    private $_router;
    private $_view;
    private $_user;

    public function __construct(Router $router, View $view)
    {
        $this->_view = $view;
        $this->_router = $router;
        $this->_user = new User();

        $this->_view->_moteurTpl->addGlobalVar('user', $this->_user);
        $this->_view->_moteurTpl->addGlobalVar('assets', AssetsManager::getAssetsFolder());
        $this->initViewContent();
    }

    public function checkAuthorization($sudoers)
    {
        return in_array($this->_user->sudo, $sudoers);
    }

    private function initViewContent()
    {
        if ($this->_user->_status == User::LOGON) {
            $this->_view->makeRightHeader('form/logon.tpl');
        } elseif ($this->_user->_status == User::LOGOFF) {
            $this->_view->makeRightHeader('form/logoff.tpl');
        }
    }

    public function logOnUser()
    {
        if ($this->_user->connect($_POST['username'], $_POST['password'])) {
            $this->_view->showAlert(array('type' => 'info', 'title' => 'Bienvenue', 'message' => 'Bienvenue sur YUDb '.$this->_user->username.' !!!'));
        } else {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Connexion échoué', 'message' => "Nous n'avons pas trouver votre profil dans la base de données."));
        }
        $this->initViewContent();
    }

    public function logOffUser()
    {
        $this->_user->disconnect();
        $this->_view->showAlert(array('type' => 'info', 'title' => 'See You Soon !', 'message' => 'Merci de votre visite et à la prochaine !!!'));

        $this->initViewContent();
    }

    public function newUser()
    {
        if (
            count($_POST['username']) == 0 ||
            count($_POST['email']) == 0 ||
            count($_POST['password']) == 0 ||
            !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Données invalides', 'message' => 'Les données que vous avez renseignés sont insuffisante ou invalides.'));
            $this->_view->showNewUserForm();
        } else {
            $this->_user->register($_POST['username'], $_POST['password'], $_POST['email']);
            if ($this->_user->connect($_POST['username'], $_POST['password'])) {
                $this->_view->showAlert(array('type' => 'info', 'title' => 'Bienvenue', 'message' => 'Bienvenue sur YUDb '.$this->_user->username.' !!!'));
            } else {
                $this->_view->showAlert(array('type' => 'warn', 'title' => 'Un problème est survenu', 'message' => 'Pour une raison quelconque, la connexion à échoué.'));
                $this->_view->showNewUserForm();
            }
        }
        $this->initViewContent();
    }

    public function creerCarteForm()
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_categorie as "key", nom as "value",  0 as "selected" FROM categories');
        $categories = $bdd->execute();

        $bdd->query('SELECT id_effet as "key", nom as "value",  0 as "selected" FROM effets');
        $effets = $bdd->execute();

        $bdd->query('SELECT id_attribut as "key", nom as "value",  0 as "selected" FROM attributs');
        $attributs = $bdd->execute();

        $bdd->query('SELECT id_type as "key", nom as "value",  0 as "selected" FROM types');
        $types = $bdd->execute();

        $niveaux = array();

        for ($i = 1; $i <= 13; ++$i) {
            $niveaux[] = array('key' => $i, 'value' => $i, 'selected' => false);
        }

        $this->_view->makeCarteForm(array('categories' => $categories, 'effets' => $effets, 'attributs' => $attributs, 'types' => $types, 'niveaux' => $niveaux));
    }

    public function creerCarte()
    {
        $bdd = DatabaseHelper::getBdd();

        if (!isset($_FILES['image'])) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte impossible à créer', 'message' => "La carte n'a pas pu être créer correctement."));

            return;
        }

        $bdd->beginTransaction();

        $id_carte = Carte::create($this->_user->getId(), $_POST['nom'], $_POST['niveau'], $_POST['attaque'], $_POST['defense'], $_POST['categorie'], $_POST['effet'], $_POST['attribut'], $_POST['types'], $_POST['description']);

        if (!$id_carte) {
            $bdd->rollback();
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte impossible à créer', 'message' => "La carte n'a pas pu être créer correctement."));

            return;
        }

        AssetsManager::addFileJPG($_FILES['image']['tmp_name'], 'cartes', $id_carte.'.jpg');
        if (!AssetsManager::resizeJPG('cartes/'.$id_carte.'.jpg', 'cartes/'.$id_carte.'.jpg', 342, 492)) {
            $bdd->rollback();
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte impossible à créer', 'message' => "La carte n'a pas pu être créer correctement."));

            return;
        }

        if (!AssetsManager::resizeJPG('cartes/'.$id_carte.'.jpg', 'cartes/'.$id_carte.'_thumb.jpg', 138, 200)) {
            $bdd->rollback();
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte impossible à créer', 'message' => "La carte n'a pas pu être créer correctement."));

            return;
        }

        $bdd->commit();
        $this->_view->showCarte(new Carte($id_carte));
    }

    public function listeCartes()
    {
        $page = null;
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_carte FROM cartes');

        $cartes = $bdd->forEachRow(array($this, 'constructCarte'));
        $this->_view->showListeCartes($cartes);
    }

    public function listeMesCartes()
    {
        $page = null;
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_carte FROM cartes WHERE id_utilisateur='.$this->_user->getId());

        $cartes = $bdd->forEachRow(array($this, 'constructCarte'));
        $this->_view->showListeCartes($cartes);
    }

    public function constructCarte(&$row)
    {
        $row['carte'] = new Carte($row['id_carte']);
    }

    public function vueCarte()
    {
        $this->_view->showCarte(new Carte($_GET['carte']));
    }

    public function deleteCarte()
    {
        $carte = new Carte($_GET['carte']);

        if ($carte->id == null) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte introuvable', 'message' => "La carte n'existe pas"));
            $this->listeMesCartes();

            return;
        }

        if ($carte->utilisateur->id != $this->_user->getId() && $this->_user->sudo != User::ADMIN) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Permission insuffisante', 'message' => "Vous n'êtes pas autorisée à supprimer cette carte"));
            $this->_view->showCarte($carte);

            return;
        }
        if (!$carte->delete()) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Erreur', 'message' => "La carte n'a pas pu être supprimé"));
            $this->_view->showCarte($carte);

            return;
        }

        AssetsManager::remove('cartes/'.$carte->id.'.jpg');
        AssetsManager::remove('cartes/'.$carte->id.'_thumb.jpg');
        $carte = new Carte($carte->id);
        $this->_view->showAlert(array('type' => 'info', 'title' => 'Suppression effectuer', 'message' => 'La carte à été supprimé.'));
        $this->listeMesCartes();
    }

    public function updateCarteForm()
    {
        $carte = new Carte($_GET['carte']);

        if ($carte->id == null) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte introuvable', 'message' => "La carte n'existe pas"));
            $this->listeMesCartes();

            return;
        }

        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_categorie as "key", nom as "value",  0 as "selected" FROM categories');
        $categories = $bdd->execute();

        $bdd->query('SELECT id_effet as "key", nom as "value",  0 as "selected" FROM effets');
        $effets = $bdd->execute();

        $bdd->query('SELECT id_attribut as "key", nom as "value",  0 as "selected" FROM attributs');
        $attributs = $bdd->execute();

        //selected = 0 si type n'appartient pas aux types de la carte.
        $bdd->query('SELECT id_type as "key", nom as "value",  1 as "selected" FROM types WHERE id_type in (SELECT id_type FROM carte_types WHERE id_carte = '.$carte->id.') UNION SELECT id_type as "key", nom as "value",  0 as "selected" FROM types WHERE id_type not in (SELECT id_type FROM carte_types WHERE id_carte = '.$carte->id.')');
        $types = $bdd->execute();

        $niveaux = array();

        for ($i = 1; $i <= 13; ++$i) {
            $niveaux[] = array('key' => $i, 'value' => $i, 'selected' => false);
        }

        $carte = new Carte($carte->id);
        $this->_view->makeUpdateCarteForm(array('carte' => $carte, 'categories' => $categories, 'effets' => $effets, 'attributs' => $attributs, 'types' => $types, 'niveaux' => $niveaux));
    }

    public function updateCarte()
    {
        $carte = new Carte($_GET['carte']);

        if ($carte->id == null) {
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Carte introuvable', 'message' => "La carte n'existe pas"));
            $this->listeMesCartes();

            return;
        }

        $bdd = DatabaseHelper::getBdd();
        $bdd->beginTransaction();

        if (!$carte->update($_POST['nom'], $_POST['niveau'], $_POST['attaque'], $_POST['defense'], $_POST['categorie'], $_POST['effet'], $_POST['attribut'], $_POST['types'], $_POST['description'])) {
            $bdd->rollback();
            $this->_view->showAlert(array('type' => 'warn', 'title' => 'Erreur', 'message' => "La carte n'a pas pu être modifié"));
            $this->listeMesCartes();

            return;
        } else {
            $bdd->commit();
            $this->_view->showCarte(new Carte($_GET['carte']));
        }
    }
}
