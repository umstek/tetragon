<?php
namespace AppBundle\Form;


use Symfony\Component\Form\Test\TypeTestCase;

class SellingItemTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'Fan',
            'brand' => 'Singer',
            'model' => 'Ex001',
            'serial' => '235413t',
            'description' => '2500 rpm',
            'price' => '5000'
        );

        $form = $this->factory->create(SellingItemType::class);

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
