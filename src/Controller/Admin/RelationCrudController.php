<?php

namespace App\Controller\Admin;
use App\Entity\EntityInterface\ManyToOneEntityInterface;
use App\Entity\EntityInterface\OneToManyEntityInterface;
use App\Exception\ActionNotFoundException;
use App\Service\Flasher;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;

class RelationCrudController extends AbstractController
{

    private const ENTITY = 'Relation';
    private $doctrine;
    private $imageRepository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->imageRepository = $doctrine->getRepository("App\Entity\Image");
        $this->em = $doctrine->getManager();
    }


    /**
     * @Route("/admin/relation/action/{action}/{oneToManyEntity}/{oneToManyEntityId}/{manyToOneEntity}", name="admin_relation_action")
     */
    public function createRelation(string $action, string $oneToManyEntity, int $oneToManyEntityId, string $manyToOneEntity, Request $request)
    {
        $manyToOneEntityIds = $request->request->get('ids');

        if (!is_array($manyToOneEntityIds)) {
            $manyToOneEntityIds = array($manyToOneEntityIds);
        };

        if ($manyToOneEntityIds[0] === null) {
            $this->addFlash(
                'notice',
                Flasher::getFlashMessage('relationError1', self::ENTITY)
            );

            $route = $request->headers->get('referer');
            return $route ? $this->redirect($route) : $this->redirectToRoute("admin_index_show");

        };

        $oneToManyEntityRepository = $this->doctrine->getRepository("App\Entity\\".$oneToManyEntity);
        $manyToOneEntityRepository = $this->doctrine->getRepository("App\Entity\\".$manyToOneEntity);

        $OTMEntity = $oneToManyEntityRepository->find($oneToManyEntityId);

        $MTOEntity = $manyToOneEntityRepository->findBy(['id' => $manyToOneEntityIds]);

        if ( $OTMEntity instanceof OneToManyEntityInterface && count(array_filter($MTOEntity, function($entity) {return $entity instanceof ManyToOneEntityInterface;})) === count($MTOEntity)) {

            switch ($action) {
                
                case "remove":

                    foreach ($MTOEntity as $entity) 
                    {
                        $entity->unsetRelation($OTMEntity);
                        $this->em->persist($entity);
                    }
                    
                    $this->em->flush();

                    $objectName = "";

                    if ($MTOEntity[0] instanceof Image) {
                        foreach ($MTOEntity as $entity) {
                            $name = $entity->getUrl() . " | ";
                            $objectName .= $name;
                        }
                    }

                    $this->addFlash(
                        'remove',
                        Flasher::getFlashMessage($action, self::ENTITY, $objectName)
                    );

                    $this->addFlash(
                        'remove2',
                        '1'
                    );

                    break;

                case "add":

                    foreach ($MTOEntity as $entity) 
                    {
                        $entity->setRelation($OTMEntity);
                        $this->em->persist($entity);
                    }

                    $this->em->flush();
                    
                    $objectName = "";

                    if ($MTOEntity[0] instanceof Image) {
                        foreach ($MTOEntity as $entity) {
                            $name = $entity->getUrl() . " | ";
                            $objectName .= $name;

                            switch ($oneToManyEntity) {
                                case "Animal":
                                    $entity->setAnimalFrontImage(false);
                                    break;
                                case "BlogPost":
                                    $entity->setPostFrontImage(false);
                                    break;
                                default:
                                    break;
                            }
                        }
                        $this->em->flush();
                    }

                    $this->addFlash(
                        'notice',
                        Flasher::getFlashMessage($action, self::ENTITY, $objectName)
                    );
                    $this->addFlash(
                        'remove2',
                        '1'
                    );
                    break;

                case "update":

                    // no update

                    break;
                default:
                    throw new ActionNotFoundException('Akcja "' . $action . '" nie została rozpoznana przez aplikację.');
            }
        }

        $route = $request->headers->get('referer');

        return $route ? $this->redirect($route) : $this->redirectToRoute("admin_index_show");


    }
}