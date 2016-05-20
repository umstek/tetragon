<?php
/**
 * Created by PhpStorm.
 * User: Thilan
 * Date: 5/20/2016
 * Time: 7:27 AM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\Test\TypeTestCase;

class RepairingItemTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'Fan',
            'due' => new \DateTime("now", new \DateTimeZone("Asia/Colombo")), //Todo
            //'isRepaired' => true,
            'description' => '2500 rpm',
            'price' => '5000'
        );

        $form = $this->factory->create(RepairingItemType::class);

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
