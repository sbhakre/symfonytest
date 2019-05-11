<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
	/**
    * @Route("/", name="homepage")
    */
	
	public function blogList() {
		$blogs = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->findAll();
		
		return $this->render('blog/list.html.twig', [
		'blogs' => $blogs
		]);
	}
	
	/**
    * @Route("/create", name="creatpage")
    */
	
	public function createBlog(Request $request) {
		
		$form = $this->createForm(BlogType::class);
		
		$form->handleRequest($request);

		$message = '';
		if ($form->isSubmitted() && $form->isValid()) {

			$blog = $form->getData();
			
			$entityManager = $this->getDoctrine()->getManager();
			//$dateImmutable = \DateTimeInterface::createFormat('Y-m-d H:i:s', strtotime('now'));
			$blog->setCreatedon(new \DateTime());
			$blog->setUpdatedon(new \DateTime());
			$blog->setCreatedby(1);
			$blog->setUpdatedby(2);
			

			// tell Doctrine you want to (eventually) save the blog (no queries yet)
			$entityManager->persist($blog);

			// actually executes the queries (i.e. the INSERT query)
			$entityManager->flush();
			
			$message = 'Blog Created';
			
			return $this->redirectToRoute('homepage');
			// do something interesting here
		}
	
		return $this->render('blog/create.html.twig', [
		'blog_form' => $form->createView()
		]);
	}
	
	/**
    * @Route("/show/{blogid}", name="showpage")
    */
	
	public function showBlog($blogid) {
		
		$result = $this->getDetails($blogid);
		
		return $this->render('blog/show.html.twig', [
		'blog' => $result
		]);
	}
	
	/**
    * @Route("/edit/{blogid}", name="editpage")
    */
	
	public function editBlog($blogid) {
		
		return $this->render('blog/edit.html.twig', [
		]);
	}
    
	private function getDetails($id = 0) {

		if ($id) {
			$blogs = $this->getDoctrine()
			->getRepository(Blog::class)
			->find($id);
			
		} else {
			
			$blogs = $this->getDoctrine()
			->getRepository(Blog::class)
			->findAll();
		}
		
		return $blogs;
	}
}