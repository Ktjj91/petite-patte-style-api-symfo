<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\User;
use ContainerCe5Z1QF\getHashNamerService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Stripe\Exception\ApiErrorException;
use Vich\UploaderBundle\Form\Type\VichFileType;
use App\Service\StripeService;

class ProductCrudController extends AbstractCrudController
{

    public function __construct(private StripeService $service)
    {

    }
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name')->setRequired(true),
            TextareaField::new('description')->setRequired(true),
            BooleanField::new('active')->setRequired(true),
            MoneyField::new('price')->setCurrency('EUR')->setRequired(true),
            Field::new('imageFile','Image')
            ->setFormType(VichFileType::class)
            ->onlyOnForms(),
            AssociationField::new("categorie"),
            TextField::new('stripeProductId','Identifiant produit stripe')
            ->hideWhenCreating(),
            TextField::new('stripePriceId','Identifiant prix stripe)','Identifiant produit stripe')
                ->hideWhenCreating()
        ];
    }

    /**
     * @throws ApiErrorException
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $product = $entityInstance;

        if(!$product instanceof Product){
            return;
        }

        $user = $this->getUser();
        if(!$user){
          $user =   $entityManager->find(User::class, 2);
          $product->setOwner($user);
        }
        $product->setCreatedAt(new \DateTimeImmutable());
        $product->setOwner($user);
       $stripeProduct =  $this->service->createProduct($product);
        $product->setStripeProductId($stripeProduct->id);

        $stripePrice = $this->service->createPrice($product);
        $product->setStripePriceId($stripePrice->id);


        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance instanceof Product){
            return;
        }

        $existingProduct = $entityManager->getUnitOfWork()
            ->getOriginalEntityData($entityInstance);

        $oldPrice = $existingProduct['price'] ?? null;
        $newPrice = $entityInstance->getPrice();

        if ($oldPrice !== null && $oldPrice !== $newPrice) {
            if($entityInstance->getStripePriceId()){
                $this->service->deactivatePrice($entityInstance->getStripePriceId());
            }

            $stripePrice = $this->service->createPrice($entityInstance);
            $entityInstance->setStripePriceId($stripePrice->id);
        }
        $this->service->updateProduct($entityInstance);
        parent::updateEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }


}
