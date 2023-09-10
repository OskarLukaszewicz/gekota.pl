<?php

namespace App\Controller\Admin;

use App\Entity\DashboardConfig;
use App\Exception\ActionNotFoundException;
use App\Service\ChartBuilder;
use App\Service\GoogleApiDataProvider;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class DashboardController extends AbstractController 
{
    private const SECTION = "dashboard";
    private $animalRepository;
    private $blogPostRepository;
    private $eventRepository;
    private $dashboardConfigRepository;
    private $em;
    

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->eventRepository = $doctrine->getRepository("App\Entity\Event");
        $this->blogPostRepository = $doctrine->getRepository("App\Entity\BlogPost");
        $this->animalRepository = $doctrine->getRepository("App\Entity\Animal");
        $this->dashboardConfigRepository = $doctrine->getRepository("App\Entity\DashboardConfig");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin", name="admin_index_show")
     */
    public function index(Request $request, ChartBuilderInterface $chartBuilder) 
    { 
        $config = $this->getUser()->getDashboardConfig() ?: $config = new DashboardConfig();
        
        $animals = $this->animalRepository->findBy([],['createdAt' => "DESC"], $config->getAnimalsNum());
        $posts = $this->blogPostRepository->findBy([],['createdAt' => "DESC"], $config->getBlogPostsNum());
        $events = $this->eventRepository->getEventsByDateRange($config->getEventsWeeksNum());

        $dataProvider = new GoogleApiDataProvider();
        $credentials = $this->getParameter('google.api.credentials');
        $dataProvider->provideData($request, $credentials, 2);

        $chart = ChartBuilder::generateChartsForDashboard($chartBuilder);

        return $this->render('admin/dashboard.html.twig', ['animals' => $animals, 'posts' => $posts, 'events' => $events, 'config' => $config, 'chart' => $chart]);
    }

    /**
     * @Route("/admin/action", name="admin_dashboard_action")
     */
    public function configAction(Request $request)
    {
        $action = $request->request->get('action');
        $value = $request->request->get('value');

        $config = $this->getUser()->getDashboardConfig();

        switch ($action) {
            case 'updateAnimalsValue':
                $config->setAnimalsNum(intval($value));
                break;
            case 'updatePostsValue':
                $config->setBlogPostsNum(intval($value));
                break;
            case 'updateEventsValue':
                $config->setEventsWeeksNum(intval($value));
                break;
            case 'updateNotesValue':
                $config->setNotes($value);
                break;
            default:
                throw new ActionNotFoundException('Akcja ' . $action . ' nie została rozpoznana przez aplikację.');
                break;
        }

        $this->em->flush();

        $route = $request->headers->get('referer');

        return $route ? $this->redirect($route) : $this->redirectToRoute("admin_index_show");
    }
}