<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/content")
 */
class ContentController extends AbstractController
{
    const ITEMS_PER_PAGE = 9;

    /**
     * @Route ("/toolbox", name="toolbox")
     * @return Response
     */
    public function toolBox(): Response
    {
        return $this->render('toolBox.html.twig');
    }

    /**
     * @Route ("/info/{category}", name="info")
     * @param ContentRepository $contentRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function showContent(
        ContentRepository $contentRepository,
        PaginatorInterface $paginator,
        Request $request,
        Category $category
    ): Response {

        $data = $contentRepository->findBy(['category' => $category->getId()]);
        $contents = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            self::ITEMS_PER_PAGE
        );

        return $this->render('nemeaContent.html.twig', [
            'contents' => $contents,
        ]);
    }
    /**
     * @Route("/admin/index", name="content_index", methods={"GET"})
     */
    public function index(ContentRepository $contentRepository): Response
    {
        return $this->render('content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="content_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('content_index');
        }

        return $this->render('content/new.html.twig', [
            'content' => $content,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="content_show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        return $this->render('content/show.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="content_article", methods={"GET"})
     */
    public function showArticle(Content $content): Response
    {
        return $this->render('content/show_article.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Content $content): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_index');
        }

        return $this->render('content/edit.html.twig', [
            'content' => $content,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="content_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Content $content): Response
    {
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($content);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_index');
    }
}
