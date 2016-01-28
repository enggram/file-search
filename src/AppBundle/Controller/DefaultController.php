<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle::layout.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    public function searchAction(Request $request)
    {
        $data = $request->request->all();
        if ($request->getMethod() == 'POST') {
            $keyword = $data['search_keyword'];
            $filePath = (__DIR__).'/../files';
            $finder = new Finder();

            $finderObj = $finder->in($filePath)->files();

            $data = $finderObj->name($keyword.'*');

            if ($data->count() == 0) {
                $finder = new Finder();
                $finderObj = $finder->in($filePath)->files();
                $data = $finderObj->contains($keyword);
            }else{
                $data = $finderObj->contains($keyword);
            }

            foreach ($data as $file) {
                echo $file->getRealpath() . PHP_EOL;
            }
        }
        return new JsonResponse("Success");

    }
}
