<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $faker = Faker\Factory::create('fr_FR');
        $places= array();
        for($i=0; $i<10 ; $i++)
        {
            $place = new Place();
            $place->setNom($faker->name);
            $place->setAdresse($faker->address);
            $em= $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();
            $places[]= $place;
        }

        return new Response(var_dump($places));
    }
}
