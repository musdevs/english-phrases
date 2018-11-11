<?php

namespace App\Controller;

use App\Entity\Phrase;
use App\Repository\PhraseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhraseController extends ApiController
{
    /**
     * @Route("/phrases")
     * @Method("GET")
     *
     * @param PhraseRepository $phraseRepository
     *
     * @return JsonResponse
     */
    public function index(PhraseRepository $phraseRepository)
    {
        $phrases = $phraseRepository->transformAll();
        return $this->respond($phrases);
    }

    /**
     * @Route("/phrases/{id}")
     * @Method("GET")
     *
     * @param $id
     * @param PhraseRepository $phraseRepository

     * @return JsonResponse
     */
    public function show($id, PhraseRepository $phraseRepository)
    {
        $phrase = $phraseRepository->find($id);

        if (!$phrase) {
            return $this->respondNotFound();
        }

        $phrase = $phraseRepository->transform($phrase);

        return $this->respond($phrase);
    }

    /**
     * @Route("/phrases")
     * @Method("POST")
     *
     * @param Request $request
     * @param PhraseRepository $phraseRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create(Request $request, PhraseRepository $phraseRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (!$request->get('source')) {
            return $this->respondValidationError('Please provide a valid source phrase!');
        }

        if (!$request->get('translation')) {
            return $this->respondValidationError('Please provide a valid translation phrase!');
        }

        $phrase = new Phrase();
        $phrase->setSource($request->get('source'));
        $phrase->setTranslation($request->get('translation'));
        $em->persist($phrase);
        $em->flush();

        return $this->respondCreated($phraseRepository->transform($phrase));
    }
}
