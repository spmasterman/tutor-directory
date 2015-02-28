<?php

namespace Fitch\FrontEndBundle\Controller;

use Fitch\CommonBundle\Exception\EntityNotFoundException;
use Fitch\UserBundle\Entity\User;
use Fitch\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Persist the state of an open/close element,.
     *
     * Stores this as a EAV value on user, creates the definition if necessary
     *
     * @Route("/widget/control",
     *      name="widget_control",
     *      options={"expose"=true},
     *      condition="request.request.has('key') and request.request.get('key') > ''",
     *      condition="request.request.has('state') and request.request.get('state') matches '/open|closed/i'"
     * )
     *
     * @Method("POST")
     * @Template()
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function widgetControlAction(Request $request)
    {
        try {
            $key = $request->request->get('key');
            $state = $request->request->get('state');
            /** @var User $user */
            $user = $this->getUser();

            $manager = $this->getUserManager();

            try {
                $attribute = $manager->findAttributeByName($user, $key);
            } catch (EntityNotFoundException $e) {
                $manager->createWidgetControlDefinition($key);
                $manager->refreshUser($user);
                $attribute = $manager->findAttributeByName($user, $key);
            }
            $attribute->setValue($state);
            $manager->saveEntity($user);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->get('fitch.manager.user');
    }
}
