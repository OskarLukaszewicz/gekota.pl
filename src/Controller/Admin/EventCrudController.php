<?php

namespace App\Controller\Admin;
use App\Exception\ActionNotFoundException;
use App\Exception\ObjectByIdNotFoundException;
use App\Form\Type\EventType;
use App\Service\Flasher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;


class EventCrudController extends AbstractController
{
    private const ENTITY = "Event";
    private const SECTION = "events";
    private $eventRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->eventRepository = $doctrine->getRepository("App\Entity\Event");
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/events/{id?}", name="admin_events_show")
     */
    public function index(?int $id)
    {
        $events = $this->eventRepository->findBy([] , ['date' => 'ASC']);
        
        $selectedEvent = ($id ? $this->eventRepository->find($id) : null);
        
        return $this->render('admin/events.html.twig', ['events' => $events, 'selectedEvent' => $selectedEvent, 'id' => $id]);
    }
    
    /**
     * @Route("/admin/events/action/{action}/{id?}", name="admin_events_action")
     */
    public function eventAction(string $action, ?int $id, Request $request )
    {
        switch ($action) {
            case "remove":
                $event = $this->eventRepository->find($id);

                if (!$event) throw new ObjectByIdNotFoundException($this->eventRepository->getClassName() ,$id);

                $this->em->remove($event);
                
                $this->em->flush();

                $this->addFlash(
                    'notice',
                    Flasher::getFlashMessage($action, self::ENTITY)
                 );
                
                return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                
            case "create":
                $event = new Event();
                $form = $this->createForm(EventType::class, $event);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $event = $form->getData();
                    $this->em->persist($event);
                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY)
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                    
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            case "update":
                $event = $this->eventRepository->find($id);

                if (!$event) throw new ObjectByIdNotFoundException($this->eventRepository->getClassName() ,$id);

                $form = $this->createForm(EventType::class, $event);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $event = $form->getData();

                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY)
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            default:
                throw new ActionNotFoundException('Akcja ' . $action . ' nie została rozpoznana przez aplikację.');
        }
    }
}