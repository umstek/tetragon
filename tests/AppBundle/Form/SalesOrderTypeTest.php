<?php
/**
 * Created by PhpStorm.
 * User: Thilan
 * Date: 5/20/2016
 * Time: 7:44 AM
 */


namespace AppBundle\Form;


use Symfony\Component\Form\Test\TypeTestCase;

class SalesOrderTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'customerId' => '768766',
            'date' => new \DateTime("now", new \DateTimeZone("Asia/Colombo")),
            'salesClerkId' => '2500 rpm',
        );

        $form = $this->factory->create(SalesOrderType::class);

//        $item = SellingItem::fromArray($formData);

// submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($item, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
