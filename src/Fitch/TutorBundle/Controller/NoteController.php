<?php

namespace Fitch\TutorBundle\Controller;

use Exception;
use Fitch\TutorBundle\Model\NoteManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Note controller.
 *
 * @Route("/editor/note")
 */
class NoteController extends Controller
{
    /**
     * @Route(
     *      "/remove",
     *      name="note_ajax_remove",
     *      options={"expose"=true},
     *      condition="request.request.has('pk') and request.request.get('pk') > 0"
     * )
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        try {
            $note = $this->getNoteManager()->findById($request->request->get('pk'));

            if (!$note) {
                throw new NotFoundHttpException('Note does not exist!');
            }

            $this->getNoteManager()->removeEntity($note);
        } catch (Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @return NoteManagerInterface
     */
    private function getNoteManager()
    {
        return $this->get('fitch.manager.note');
    }
}
