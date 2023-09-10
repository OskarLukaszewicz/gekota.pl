<?php

namespace App\Controller\Admin;

use App\Entity\DashboardConfig;
use App\Exception\ActionNotFoundException;
use App\Exception\ObjectByIdNotFoundException;
use App\Form\Type\UserType;
use App\Service\Flasher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserCrudController extends AbstractController 
{

    private const ENTITY = "User";
    private const SECTION = "users";
    private $userRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->userRepository = $doctrine->getRepository('App\Entity\User');
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("/admin/users/{nickname?}", name="admin_users_show")
     */
    public function index(?string $nickname)
    {
        $users = $this->userRepository->findAll();

        $selectedUser = ($nickname ? $this->userRepository->findOneBy(['nickname' => $nickname]) : null);

        return $this->render('admin/users.html.twig', ['users' => $users, 'selectedUser' => $selectedUser, 'nickname' => $nickname]);

    }

    /**
     * @Route("/admin/users/action/{action}/{id?}", name="admin_users_action")
     */
    public function userAction(string $action, ?int $id, Request $request, UserPasswordHasherInterface $hasher)
    {
        switch ($action) {
            case "remove":
                $user = $this->userRepository->find($id);
                if (!$user) throw new ObjectByIdNotFoundException($this->userRepository->getClassName() ,$id);
                foreach($user->getPosts() as $post) {
                    $post->setAuthor(null);
                }
                $this->em->remove($user);
                $this->em->flush();

                $this->addFlash(
                    'notice',
                    Flasher::getFlashMessage($action, self::ENTITY , $user->getNickname())
                 );
                 
                return $this->redirectToRoute("admin_" . self::SECTION . "_show");

            case "create":
                $user = new User();
                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $user = $form->getData();
                    $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
                    $dashboardConfig = new DashboardConfig();
                    $this->em->persist($dashboardConfig);
                    $user->setDashboardConfig($dashboardConfig);
                    $this->em->persist($user);
                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY , $user->getNickname())
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);
                
            case "update":
                
                $user = $this->userRepository->find($id);

                if (!$user) throw new ObjectByIdNotFoundException($this->userRepository->getClassName() ,$id);

                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $user = $form->getData();

                    $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

                    $this->em->flush();

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY , $user->getNickname())
                     );

                    return $this->redirectToRoute("admin_" . self::SECTION . "_show");
                }

                return $this->renderForm('admin/actionPage.html.twig', ['form' => $form]);

            case 'updateRoles':

                $user = $this->userRepository->find($id);

                if (!$user) throw new ObjectByIdNotFoundException($this->userRepository->getClassName() ,$id);

                $user->setRoles($request->request->get('roles'));

                $this->em->flush();

                $route = $request->headers->get('referer');

                return $route ? $this->redirect($route) : $this->redirectToRoute("admin_" . self::SECTION . "_show");

            default:
                throw new ActionNotFoundException('Akcja "' . $action . '" nie została rozpoznana przez aplikację.');
        }
    }

}