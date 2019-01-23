<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Get("/places")
     */
    public function getPlacesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $places = $em->getRepository(Place::class)
            ->findAll();
        /*$places= $em->getRepository('AppBundle:Place')
            ->findAll();*/

        /*$nouveauTab=array();
        foreach ($places as $place)
        {
            $nouveauTab[]= array(
                'id'=> $place->getId(),
                'nom' => $place->getNom(),
                'adresse' => $place->getAdresse(),
            );
        }

        // Récupération du view handler
        $viewHandler = $this->get('fos_rest.view_handler');*/

        // Création d'une vue FOSRestBundle
        $view = View::create($places);
        $view->setFormat('json');

        // Gestion de la réponse
        return $view;
    }

    /**
     * @Route("/places/{place_id}", name="places_one")
     * @Method({"GET"})
     */
    public function getPlaceAction(Request $request)
    {
        $place = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Place')
            ->find($request->get('place_id'));
        /* @var $place Place */

        if (empty($produit)) {
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $place->getId(),
            'nom' => $place->getNom(),
            'adresse' => $place->getAdresse(),
        ];

        return new JsonResponse($formatted);
    }

//    /**
//     * @Rest\View(statusCode=Response::HTTP_CREATED)
//     * @Rest\Post("/places")
//     */
//    public function postPlacesAction(Request $request)
//    {
//
//        $place = new Place();
//        $place->setNom($request->get('nom'))
//            ->setAdresse($request->get('adresse'));
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($place);
//        $em->flush();
//
//        return $place;
//    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/places")
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();

            $view = View::create($place);
            $view->setFormat('json');
            return $view;
        } else {
            return $form;
        }
    }


}
