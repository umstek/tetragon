<?php

namespace AppBundle\Form;


use AppBundle\Entity\Customer;
use Symfony\Component\Form\Test\TypeTestCase;

class CustomerTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'gamma1',
            'address' => '',
            'phone' => '111',
            'email' => 'gamma1@gm.sd',
            'nic' => ''
        );

        $form = $this->factory->create(CustomerType::class);

        $customer = Customer::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($customer, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
