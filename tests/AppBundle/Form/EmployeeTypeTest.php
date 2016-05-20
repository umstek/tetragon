<?php
/**
 * Created by PhpStorm.
 * User: Thilan
 * Date: 5/20/2016
 * Time: 7:20 AM
 */


namespace AppBundle\Form;


use Symfony\Component\Form\Test\TypeTestCase;

class EmployeeTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'thilan',
            'id' => '140066T',
            'address' => 'abc lane, klm.',
            'phone' => '+94757995753',
            'email' => 'thilan.rc@gmail.com',
            'nic' => '940631530V'
        );

//        $form = $this->factory->create(EmployeeType::class);
//
////        $item = SellingItem::fromArray($formData);
//
//// submit the data to the form directly
//        $form->submit($formData);
//
//        $this->assertTrue($form->isSynchronized());
////        $this->assertEquals($item, $form->getData());
//
//        $view = $form->createView();
//        $children = $view->children;
//
//        foreach (array_keys($formData) as $key) {
//            $this->assertArrayHasKey($key, $children);
//        }
    }
}
