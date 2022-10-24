<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    /**
     * @Route("admin/albums", name="admin_albums",methods={"GET"})
     */
    public function listeAlbumsCompletePaginee(AlbumRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $albums=$paginator->paginate(
            $repo->listeAlbumsCompletePaginee(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('admin/album/listeAlbums.html.twig', [
            'lesAlbums' => $albums
        ]);
        
    }

    /**
     * @Route("admin/album/ajout", name="admin_album_ajout",methods={"GET","POST"})
     * @Route("admin/album/modif/{id}", name="admin_album_modif",methods={"GET","POST"})
     */
    public function ajoutAlbum(Album $album=null,Request $request, EntityManagerInterface $manager): Response
    {
        if ($album==null) {
            $album=new Album;
            $mode="ajouté";
        }
        else{
            $mode="modifié";
        }
        
        $form=$this->createForm(AlbumType::class,$album);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($album);
            $manager->flush();
            $this->addFlash("success","Le album à bien été $mode");
            return $this->redirectToRoute('admin_albums');
        }
        return $this->render('admin/album/formAjoutModifAlbum.html.twig', [
            'formAlbum' => $form->createView()
        ]);
        
    }

    /**
     * @Route("admin/album/suppression/{id}", name="admin_album_suppression",methods={"GET"})
     */
    public function suppressionAlbum(Album $album,EntityManagerInterface $manager): Response
    {
        $nbMorceaux=$album->getMorceaux()->count();
        if($nbMorceaux>0){
            $this->addFlash("danger","Impossible de supprimer le album car il possède $nbMorceaux morceau(x) associés");
        }else{
            $manager->remove($album);
            $manager->flush();
            $this->addFlash("success","Le album à bien été supprimé");
        }
        return $this->redirectToRoute('admin_albums');
    }

}
