<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimcore\Model\DataObject\Association;

class DefaultController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */

    public function defaultAction(Request $request): Response
    {
        // ID der FußballClubs
        $objects = Association::getList(['parentId' => 5]);

        return $this->render('default/football.html.twig', [
            'objectList' => $objects,
        ]);
    }
    
    public function showAction($id): Response
    {
        if (isset($id)) {
            $asset = Association::getById($id);
        } else {
            $asset = Association::getById(2);
        }

        return $this->render('default/association.html.twig', [
            'object' => $asset,
        ]);
    }

    public function footballAction(Request $request): Response
    {
        // $objects = Association::getList(['parentId' => 5]);

        // Objekt-ID des Pimcore-Objekts, das Sie anzeigen möchten
        // $objectId = 7;

        // Abrufen des Pimcore-Objekts
        // $object = Association::getById($objectId);

        // Extrahieren der benötigten Daten aus dem Objekt
        // $name = $object->getName();
        // $description = $object->getDescription();
        // $image = $object->getLogo();

        // Übergabe der Daten an die View
        // return $this->render('default/football.html.twig', [
        //     'name' => $name,
        //     // 'description' => $description,
        //     // 'image' => $image,
        // ]);
        return null;
    }
}