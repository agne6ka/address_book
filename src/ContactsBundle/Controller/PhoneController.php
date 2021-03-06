<?php

namespace ContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use ContactsBundle\Entity\Phone;

/**
 * @Route("/{person_id}/phone")
 */
class PhoneController extends Controller
{
    private function getForm($person_id, $phone, $actionUrl = false){
        if($actionUrl==false){
            $actionUrl = $this->generateUrl('contacts_phone_create',['person_id'=>$person_id]);
        }
        $form = $this->createFormBuilder($phone)
            ->setAction($actionUrl)
            ->setMethod('POST')
            ->add('number')
            ->add('type')
            ->add('submit', 'submit',['label'=>'Add'])
            ->getForm();
        return $form;
    }
    /**
     * @Route("/new")
     */
    public function newAction($person_id)
    {
        $phone = new Phone();
        $form = $this->getForm($person_id,$phone);
        return $this->render('ContactsBundle:Phone:new.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/create")
     */
    public function createAction(Request $req,$person_id)
    {
        $p = new Phone();
        $form = $this->getForm($person_id, $p);
        $form->handleRequest($req);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            /**
             * Todo: add if person exist... else throw 404
             */
            $person = $em->getRepository('ContactsBundle:Person')->find($person_id);
            $person->addPhone($p);

            $p->setPerson($person);
            $em->persist($p);
            $em->flush($p);
            return $this->redirect(
                $this->generateUrl(
                    'contacts_person_show',
                    [
                        'id'=>$person_id
                    ]
                )
            );
        }
        return $this->render('ContactsBundle:Phone:new.html.twig', array(
            'form'=>$form
        ));
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction($person_id)
    {
        return $this->render('ContactsBundle:Phone:delete.html.twig', array(
            // ...
        ));
    }

}
