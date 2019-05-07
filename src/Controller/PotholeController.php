<?php


namespace App\Controller;


use App\Forms\UploadPotholeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PotholeController extends AbstractController
{
    /**
     * Upload a new pothole image.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $form = $this->createForm(UploadPotholeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var File $image
             */
            $image =$form->getData()['image'];

            $image->move($this->getParameter('kernel.project_dir') . '/public/uploads', uniqid());
            return JsonResponse::create(['ok']);

        }

        return JsonResponse::create(['not ok']);
    }

    /**
     * List the entire pothole images.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $path = $this->getParameter('kernel.project_dir') . '/public/uploads/';
        $files = array_slice(scandir($path), 2);

        return JsonResponse::create(['data' => array_map(function ($file) use ($path) {
            return 'http://localhost:8000/uploads/' . $file;
        }, $files)]);
    }
}
