<?php

namespace App\Controller;

use App\Entity\Business;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\BusinessType;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BusinessController extends Controller
{
	/**
     * @Route("/", name="home")
     * @Method({"GET"})
     */	
    public function index(Request $request)
    {
    	$business = null;
        $q = $request->get('q');
        if(!empty($q)){
	        $business = $this->getDoctrine()->getRepository(Business::class)
	        	->createQueryBuilder("B")
	        	->innerJoin('B.id_city', 'C')
	            ->orWhere("LOWER(B.title) LIKE LOWER('%{$q}%')")
	            ->orWhere("LOWER(B.address) LIKE LOWER('%{$q}%')")
	            ->orWhere("LOWER(B.zipcode) LIKE LOWER('%{$q}%')")
	            ->orWhere("LOWER(C.name) LIKE LOWER('%{$q}%')")
	            //->orWhere("B.category = :category")
	            ->getQuery()
	            ->getResult();
        }

     	return $this->render('business/index.html.twig', array(
     		'business' => $business,
     		'q' => $q,
     	));
    }

    /**
     * @Route("/admin", name="admin")
     * @Method({"GET"})
     */
    public function admin()
    {
        $business = $this->getDoctrine()->getRepository(Business::class)->findAll();
     	return $this->render('admin/index.html.twig', array('business' => $business));
    }

    /**
     * @Route("/business/view/id/{id}", name="business_view")
     */
    public function view($id) {
      $business = $this->getDoctrine()->getRepository(Business::class)->find($id);
      return $this->render('business/view.html.twig', array('business' => $business));
    }

    /**
	 * @Route("/admin/business/new", name="new_business")
	 * Method({"GET", "POST"})
	 */
    public function new(Request $request) {
		
        $business = new Business();
        $form = $this->createForm(BusinessType::class, $business);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($business);
            $entityManager->flush();

            return $this->redirectToRoute('business_view', [ 'id' => $business->getId() ]);
        }

    	return $this->render('admin/business/new.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/state/getCities", name="state_get_cities")
	 * Method({"GET", "POST"})
	 */
    public function getCitiesAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $citiesRepository = $entityManager->getRepository("App:City");
        
        $cities = $citiesRepository->createQueryBuilder("C")
            ->where("C.id_state = :stateid")
            ->setParameter("stateid", $request->query->get("stateid"))
            ->getQuery()
            ->getResult();
        
        $responseArray = array();
        foreach($cities as $city){
            $responseArray[] = array(
                "id" => $city->getId(),
                "name" => $city->getName()
            );
        }
        
        return new JsonResponse($responseArray);
    }
}