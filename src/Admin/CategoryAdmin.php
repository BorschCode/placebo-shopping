<?php

namespace App\Admin;

use App\Entity\Category;
use App\Enum\Theme;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CategoryAdmin extends AbstractAdmin
{
    public function toString(object $object): string
    {
        return $object instanceof Category ? $object->getName() : 'Category';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', TextType::class)
            ->add('slug', TextType::class, ['help' => 'Unique URL-safe identifier, e.g. electronics'])
            ->add('icon', TextType::class, ['required' => false, 'label' => 'Icon (emoji)'])
            ->add('themeType', EnumType::class, [
                'class' => Theme::class,
                'choice_label' => fn(Theme $t) => $t->label(),
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ->add('slug');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name')
            ->add('icon', null, ['label' => 'Icon'])
            ->add('slug')
            ->add('themeType', null, ['label' => 'Theme'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => ['show' => [], 'edit' => [], 'delete' => []],
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('icon')
            ->add('themeType');
    }
}
