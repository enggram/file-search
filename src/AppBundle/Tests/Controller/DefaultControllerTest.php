<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Crawling the ajax URL and checking the expected results
     */
    public function testDataSource()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/result-json', array('keyword' => ''));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/result-json', array('keyword' => 'ty'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('typically', $this->getKeywordData($client->getResponse(),'typically'));
        $crawler = $client->request('GET', '/result-json', array('keyword' => 'lorum'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('lorum', $this->getKeywordData($client->getResponse(),'lorum'));
    }

    public function getKeywordData($response,$keyword)
    {
        $responseData = '';
        $data = json_decode($response->getContent(),true);
        if(count($data)>0){
            foreach($data as $d)
            {
                if($d['keyword'] == $keyword){
                    $responseData .= $d['keyword'];
                }
            }
        }
        return $responseData;
    }

    /**
     * Functional test - Submitting the form and getting the expected results
     */
    public function testSearchForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Submit')->form();
        $form['search_keyword'] = 'ty';
        $crawler = $client->submit($form);


        $this->assertTrue($crawler->filter('html:contains("typically")')->count() > 0);

    }
}