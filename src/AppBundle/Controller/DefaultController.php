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
        return $this->render('AppBundle::layout.html.twig', array('resultArray' => ''));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $resultArray = array();
        $data = $request->request->all();
        if ($request->getMethod() == 'POST') {
            $keyword = preg_replace('/^([^(]*).*$/', '$1', $data['search_keyword']);
            $resultArray = $this->resultJsonData($keyword,true);
        }
        return $this->render('AppBundle::layout.html.twig', array('resultArray' => $resultArray));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * Ajax call action
     */
    public function getResultJsonAction(Request $request)
    {
        $keyword = $request->get('keyword');
        $resultData = $this->resultJsonData($keyword);
        return new JsonResponse($resultData);
    }

    /**
     * @param $keyword
     * @param bool|false $ext
     * @return array
     * Finder object and getting results for file and file contents
     */
    public function resultJsonData($keyword,$ext=false)
    {
        $resultArray = array();
        $filePath = (__DIR__).'/../files';
        $nameSearch = new Finder();
        $contentSearch = new Finder();

        $nameFinderObj = $nameSearch->in($filePath)->files();

        $nameFinderObj->name($keyword.'*');

        $contentFinderObj = $contentSearch->in($filePath)->files();
        $contentFinderObj->contains($keyword);


        foreach ($nameFinderObj as $file) {
            $filename = preg_replace('/^([^.]*).*$/', '$1', $file->getFileName());
            $resultArray[] = array('keyword' => $keyword, 'filename' => 'filename: '.$filename);
        }
        /**
         * Find the string in the file content
         */
        foreach ($contentFinderObj as $file) {
            $contents = $file->getContents();
            preg_match('/\w*'.$keyword.'\w*/', $contents, $matches);
            $filename = $file->getFileName();
            if(!$ext){
                $filename = preg_replace('/^([^.]*).*$/', '$1', $file->getFileName());
            }
            $resultArray[] = array('keyword' => $matches[0], 'filename' => 'filename: '.$filename);
        }

        return $resultArray;
    }
}
