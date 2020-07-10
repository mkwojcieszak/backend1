<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Person;
use App\Entity\Group;
use App\Form\PersonType;
use App\Form\GroupType;
use App\Repository\PersonRepository;
use App\Repository\GroupRepository;


/**
 * @Route("/admin", name="admin.")
 */

class MainController extends AbstractController
{
    /**
     * @Route("/panel", name="panel")
     */
    public function index()
    {
        return $this->render('admin/panel.html.twig');
    }

    /**
     * @Route("/groups", name="groups")
     */

    public function groups(GroupRepository $repo) {
        $groups = $repo->findAll();

        return $this->render('admin/groups.html.twig', [
            'groups' => $groups
        ]);
     }

    /**
     * @Route("/addgroup", name="addgroup")
     */

    public function addGroup(Request $request) {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $group->setCreatedAt(new \DateTime());
            $group->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em-> persist($group);
            $em-> flush();

            return $this->redirect($this->generateUrl('admin.groups'));
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView()
        ]);
     }

     /**
     * @Route("/editgroup/{id}", name="editgroup")
     */

    public function editGroup(Request $request, GroupRepository $repo, $id) {
        $group = $repo->find($id);
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $group->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em-> persist($group);
            $em-> flush();

            return $this->redirect($this->generateUrl('admin.groups'));
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView()
        ]);
     }

     /**
     * @Route("/deletegroup/{id}", name="deletegroup")
     */

    public function deleteGroup(Request $request, GroupRepository $repo, $id, PersonRepository $persRepo) {
        $group = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $persons = $persRepo->findBy(array('group' => $id));
        foreach($persons as $person) {
            $person->setGroup(null);
            $em->persist($person);
            $em-> flush();
        }

        $em-> remove($group);
        $em-> flush();
        return $this->redirect($this->generateUrl('admin.groups'));
    }



    /**
     * @Route("/persons", name="persons")
     */
    public function persons(PersonRepository $repo)
    {
        $persons = $repo->findAll();
        return $this->render('admin/persons.html.twig', [
            'persons' => $persons
        ]);
    }

    /**
     * @Route("/addperson", name="addperson")
     */
    public function addPerson(Request $request) {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $person->setCreatedAt(new \DateTime());
            $person->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em-> persist($person);
            $em-> flush();

            return $this->redirect($this->generateUrl('admin.persons'));
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView()
        ]);
     }

     /**
     * @Route("/editperson/{id}", name="editperson")
     */

    public function editPerson(Request $request, PersonRepository $repo, $id) {
        $person = $repo->find($id);
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $person->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em-> persist($person);
            $em-> flush();

            return $this->redirect($this->generateUrl('admin.persons'));
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView()
        ]);
     }

    /**
     * @Route("/deleteperson/{id}", name="deleteperson")
     */

    public function deletePerson(Request $request, PersonRepository $repo, $id) {
        $person = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em-> remove($person);
        $em-> flush();
        return $this->redirect($this->generateUrl('admin.persons'));
    }

}
