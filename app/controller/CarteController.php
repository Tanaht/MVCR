<?php

namespace app\controller;

use app\config\DatabaseHelper;
use app\model\Carte;
use app\router\Request;
use app\services\AssetsManager;
use app\view\Fragment;
use app\view\Response;
use formBuilder\Form;

class CarteController extends AbstractController
{
    public function create(Request $request, Response $response)
    {
        $bdd = DatabaseHelper::getBdd();
        $request->denyForRoles(array('USER'), $response);
        $insertCarteForm = new Form('insertCarteForm', 'carteForm.tpl');

        $insertCarteForm->add('nom', null, true);
        $insertCarteForm->add('attaque');
        $insertCarteForm->add('defense');
        $insertCarteForm->add('description');
        $insertCarteForm->add('image', null, true);
        $insertCarteForm->add('niveau', true);
        $insertCarteForm->add('categorie', null, true);
        $insertCarteForm->add('effet', null, true);
        $insertCarteForm->add('attribut', null, true);
        $insertCarteForm->add('types');

        $insertCarteForm->handleRequest($request);

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

        $insertCarteForm->rememberThisForm();
        if ($request->getMethod() == Request::METHOD_POST) {
            if ($insertCarteForm->isValid()) {
                //$validContent = $this->checkInsertCarteForm($insertCarteForm);
                $validContent = true;
                if (!$validContent) {
                    $response->responseRedirect($request->getUrl());
                }

                $bdd->beginTransaction();

                $id_carte = Carte::create(
                    $request->user->utilisateur->id,
                    $insertCarteForm->get('nom'),
                    $insertCarteForm->get('niveau'),
                    $insertCarteForm->get('attaque'),
                    $insertCarteForm->get('defense'),
                    $insertCarteForm->get('categorie'),
                    $insertCarteForm->get('effet'),
                    $insertCarteForm->get('attribut'),
                    $insertCarteForm->get('types'),
                    $insertCarteForm->get('description')
                );

                if ($id_carte == false) {
                    $bdd->rollback();
                    $response->responseRedirect($request->getUrl());
                }
                $bdd->commit();
                AssetsManager::addFileJPG($_FILES['image']['tmp_name'], 'cartes', $id_carte.'.jpg');
                AssetsManager::resizeJPG('cartes/'.$id_carte.'.jpg', 'cartes/'.$id_carte.'.jpg', 342, 492);
                AssetsManager::resizeJPG('cartes/'.$id_carte.'.jpg', 'cartes/'.$id_carte.'_thumb.jpg', 138, 200);

                $response->responseRedirect('/cartes/'.$id_carte);
            } else {
                echo "IS NOT VALID";
                $response->responseRedirect($request->getUrl());
            }
        }

        $contentWrapper = $response->_w();

        $formFragment = $insertCarteForm->getFormFragment();
        $formFragment->injects(array('categories' => $categories, 'effets' => $effets, 'attributs' => $attributs, 'types' => $types, 'niveaux' => $niveaux));

        $contentWrapper->addTemplate($formFragment);

        $response->view()->inflate('content', $contentWrapper);
        $response->view()->inject('title', 'Créer Une Carte');
    }

    public function update(Request $request, Response $response)
    {
        $carte = new Carte($request->getParam('idCarte'));
        if ($carte == null) {
            $response->responseRedirect('/cartes');
        }

        $request->allowForAuthenticateUser($carte->utilisateur->username, $response);

        $bdd = DatabaseHelper::getBdd();

        $updateCarteForm = new Form('updateCarteForm', 'updateCarteForm.tpl');

        $updateCarteForm->add('nom', $carte->nom, true);
        $updateCarteForm->add('attaque', $carte->attaque);
        $updateCarteForm->add('defense', $carte->defense);
        $updateCarteForm->add('description', $carte->description);
        $updateCarteForm->add('niveau', $carte->niveau, true);
        $updateCarteForm->add('categorie', $carte->categorie->id, true);
        $updateCarteForm->add('effet', $carte->effet->id, true);
        $updateCarteForm->add('attribut', $carte->attribut->id, true);
        $updateCarteForm->add('types', $carte->types);

        $updateCarteForm->handleRequest($request);

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

        $updateCarteForm->rememberThisForm();
        if ($request->getMethod() == Request::METHOD_POST) {
            if ($updateCarteForm->isValid()) {
                //$validContent = $this->checkUpdateCarteForm($insertCarteForm);
                $validContent = true;
                if (!$validContent) {
                    $response->responseRedirect($request->getUrl());
                }

                $bdd->beginTransaction();
                $isUpdated = $carte->update(
                    $updateCarteForm->get('nom'),
                    $updateCarteForm->get('niveau'),
                    $updateCarteForm->get('attaque'),
                    $updateCarteForm->get('defense'),
                    $updateCarteForm->get('categorie'),
                    $updateCarteForm->get('effet'),
                    $updateCarteForm->get('attribut'),
                    $updateCarteForm->get('types'),
                    $updateCarteForm->get('description')
                );

                if ($isUpdated == false) {
                    $bdd->rollback();
                    $response->responseRedirect($request->getUrl());
                }
                $bdd->commit();
                $response->responseRedirect('/cartes/'.$carte->id);
            } else {
                $response->responseRedirect($request->getUrl());
            }
        }

        $contentWrapper = $response->_w();

        $formFragment = $updateCarteForm->getFormFragment();
        $formFragment->injects(array('carte' => $carte, 'categories' => $categories, 'effets' => $effets, 'attributs' => $attributs, 'types' => $types, 'niveaux' => $niveaux));

        $contentWrapper->addTemplate($formFragment);

        $response->view()->inflate('content', $contentWrapper);
        $response->view()->inject('title', 'Créer Une Carte');
    }

    public function delete(Request $request, Response $response)
    {
        $request->denyForRoles(array('USER'), $response);

        $carte = new Carte($request->getParam('idCarte'));

        if (!$carte->delete()) {
            $alert = new Fragment('frg/error/alert.tpl');
            $alert->injects(array('type' => 'warn', 'title' => 'Erreur', 'message' => "La carte n'a pas pu être supprimé"));

            $contentWrapper = $response->_w();

            $contentWrapper->addTemplate($alert);
            $response->view()->inflate('content', $contentWrapper);
            $response->view()->inject('title', 'Erreur de suppression');

            return;
        }

        AssetsManager::remove('cartes/'.$request->getParam('idCarte').'.jpg');
        AssetsManager::remove('cartes/'.$request->getParam('idCarte').'_thumb.jpg');

        $response->responseRedirect('/cartes');
    }

    public function listeAll(Request $request, Response $response)
    {
        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_carte FROM cartes');

        $rows = $bdd->execute();

        $cartesFragmentWrapper = $response->_w();
        foreach ($rows as $row) {
            $carteFragment = new Fragment('frg/carte_list_item.tpl');
            $carteFragment->inject('carte', new Carte($row['id_carte']));
            $cartesFragmentWrapper->addTemplate($carteFragment);
        }

        $listesCartesWrapper = $response->_w();

        $listesCartes = new Fragment('frg/listeCartes.tpl');
        $listesCartes->inject('title', 'Les Cartes');
        $listesCartesWrapper->addTemplate($listesCartes);
        $response->view()->inflate('content', $listesCartesWrapper);
        $response->view()->inject('title', parent::getTitle('Les Cartes'));
        $response->view()->inflate('cartes', $cartesFragmentWrapper);
    }

    public function listeMy(Request $request, Response $response)
    {
        $request->allowForAuthenticateUser($request->getParam('username'), $response);

        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_carte FROM cartes WHERE id_utilisateur='.$request->user->utilisateur->id);

        $rows = $bdd->execute();

        $cartesFragmentWrapper = $response->_w();
        foreach ($rows as $row) {
            $carteFragment = new Fragment('frg/carte_list_item.tpl');
            $carteFragment->inject('carte', new Carte($row['id_carte']));
            $cartesFragmentWrapper->addTemplate($carteFragment);
        }

        $listesCartesWrapper = $response->_w();

        $listesCartes = new Fragment('frg/listeCartes.tpl');
        $listesCartes->inject('title', 'Mes Cartes');
        $listesCartesWrapper->addTemplate($listesCartes);
        $response->view()->inflate('content', $listesCartesWrapper);
        $response->view()->inflate('cartes', $cartesFragmentWrapper);
        $response->view()->inject('title', parent::getTitle('Mes Cartes'));
    }

    public function viewOne(Request $request, Response $response)
    {
        $request->denyForRoles(array('USER'), $response);

        $bdd = DatabaseHelper::getBdd();

        $bdd->query('SELECT id_carte FROM cartes WHERE id_carte ='.$request->getParam('idCarte'));

        $rows = $bdd->execute();

        $carteFragmentWrapper = $response->_w();

        foreach ($rows as $row) {
            $carteFragment = new Fragment('frg/carte_detail.tpl');
            $carteFragment->inject('carte', new Carte($row['id_carte']));
            $carteFragmentWrapper->addTemplate($carteFragment);
        }

        $response->view()->inflate('content', $carteFragmentWrapper);
        $response->view()->inject('title', parent::getTitle('Mes Cartes'));
    }

    private function checkInsertCarteForm(Form $form)
    {
    }

    private function checkUpdateCarteForm(Form $form)
    {
    }
}
