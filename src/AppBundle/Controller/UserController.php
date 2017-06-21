<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/users")
     */
    public function getAllAction()
    {
        $result = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        if ($result === null) {
            return new View("no users found", Response::HTTP_NOT_FOUND);
        }

        return $result;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function getOneAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\Post("/user")
     * @param Request $request
     * @return View
     */
    public function postAction(Request $request)
    {

        $name = $request->get('name');
        $role = $request->get('role');

        if(empty($name) || empty($role))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }

        $data = new User;
        $data->setName($name);
        $data->setRole($role);

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();

        return new View("user added successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     * @param Request $request
     * @param $id
     * @return View
     */
    public function updateAction(Request $request, $id)
    {
        $name = $request->get('name');
        $role = $request->get('role');

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }

        if (empty($name) && empty($role)) {
            return new View("user name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!empty($name)) {
            $user->setName($name);
        }

        if (!empty($role)) {
            $user->setRole($role);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new View("user updated successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/user/{id}")
     * @param $id
     * @return View
     */
    public function deleteAction($id)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new View("deleted successfully", Response::HTTP_OK);
    }
}