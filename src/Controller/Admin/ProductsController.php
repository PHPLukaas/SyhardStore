<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ImagesRepository;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();


        return $this->render('admin/products/index.html.twig', compact('produits'));
    }
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em , SluggerInterface $slugger , PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');



        //création d'un produit
        $product = new Products();
        // Creation formulaire
        $productForm = $this->createForm(ProductsFormType::class,$product);

        //Traite de la requéte du formulaire
        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){

            //recupération image
            $images = $productForm->get('image')->getData();

            foreach ($images as $images){
                //Définition du dossier de destination
                $folder = 'products';
                //Appele du service d'ajout
                $fichier = $pictureService->add($images , $folder ,300 ,300);

                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }


            //Génération slug
            $slug = $slugger->slug($product->getName());

            $product->setSlug($slug);

            //Arrondire le prix
            //$prix = $product->getPrix() * 100;
            //$product->setPrix($prix);
            //Stockage
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes' , 'Produit ajouté avec succés');
            //redirection
            return $this->redirectToRoute('admin_products_index');
        }
        return $this->render('admin/products/ajout.html.twig',[
            'productForm'=> $productForm->createView(),
            'product'=> $product
        ]);
        //return $this->renderForm('admin/products/ajout.html.twig',compact('productForm')
            //['productForm' => $productForm ->createView()]


    }
    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em ,
                         SluggerInterface $slugger ,PictureService $pictureService, ImagesRepository $imagesRepository): Response
    {

        //On verif si user peut edit avec voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT' , $product);
        //Divise le prix
         //$prix = $product->getPrix() / 100 ;
         //$product->setPrix($prix);

        $image = $imagesRepository->findBy(['products'=>$product]);

        $product->addImage($image[0]);

        // Creation formulaire
        $productForm = $this->createForm(ProductsFormType::class,$product);

        //Traite de la requéte du formulaire
        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){

            //recupération image
            $images = $productForm->get('image')->getData();

            foreach ($images as $images){
                //Définition du dossier de destination
                $folder = 'products';
                //Appele du service d'ajout
                $fichier = $pictureService->add($images , $folder ,300 ,300);

                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }

            //Génération slug
            $slug = $slugger->slug($product->getName());

            $product->setSlug($slug);

            //Arrondire le prix
            //$prix = $product->getPrix() * 100;
            //$product->setPrix($prix);
            //Stockage
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes' , 'Produit modifié avec succés');
            //redirection
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/products/edit.html.twig',[
            'productForm'=> $productForm->createView(),
            'product'=> $product]);


    }




    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_DELETE' , $product);
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/suppression/image/{id}', name: 'delete_image' , methods: ['DELETE', 'GET'])]

    public function delete_image(Images $images , Request $request , EntityManagerInterface $em , PictureService $pictureService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $images->getId(), $data['_token'])){
            //token valide / recupération du nom de l'image
            $nom = $images->getName();
            if($pictureService->delete($nom , 'products' , 300 , 300)){
                $em->remove($images);
                $em->flush();
                return new JsonResponse(['succes'=> true], 200);
            }
            return new JsonResponse(['error'=>'Erreur de suppression'], 400);
        }
        return new JsonResponse(['error'=>'Token invalide'], 400);
    }

}