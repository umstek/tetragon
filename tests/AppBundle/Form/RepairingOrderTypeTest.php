<?php
/**
 * Created by PhpStorm.
 * User: Thilan
 * Date: 5/20/2016
 * Time: 7:38 AM
 */


namespace AppBundle\Form;

use Symfony\Component\Form\Test\TypeTestCase;

class RepairingOrderTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'customerId' => '768766',
            'technicianId' => '2500 rpm',
            'date' => new \DateTime("now", new \DateTimeZone("Asia/Colombo")),
            // 'itemsIds' => "6547887",
        );

        $form = $this->factory->create(RepairingOrderType::class);

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