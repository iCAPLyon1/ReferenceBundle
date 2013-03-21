<?php

namespace ICAP\ReferenceBundle\Tests\DataFixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Claroline\CoreBundle\Library\Fixtures\LoggableFixture;

class LoadReferenceData extends LoggableFixture implements ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    private $username;
    private $referenceBankName;
    private $nbMessages;
    private $nbSubjects;
    private $parent;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function __construct($referenceBankName, $username, $nbMessages, $nbSubjects, $parent = null)
    {
        $this->referenceBankName = $referenceBankName;
        $this->username = $username;
        $this->nbMessages = $nbMessages;
        $this->nbSubjects = $nbSubjects;
        $this->parent = $parent;
    }

    public function load(ObjectManager $manager)
    {
        $creator = $this->getContainer()->get('claroline.resource.manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('ClarolineCoreBundle:User')->findOneBy(array('username' => $this->username));
        if ($this->parent == null) {
            $root = $em->getRepository('ClarolineCoreBundle:Resource\AbstractResource')
                ->findOneBy(array('parent' => null, 'workspace' => $user->getPersonalWorkspace()->getId()));
        } else {
            $root = $this->parent;
        }

        $collaborators = $em->getRepository('ClarolineCoreBundle:User')->findByWorkspace($root->getWorkspace());
        $collaborators = $this->getContainer()
            ->get('claroline.utilities.paginator_parser')
            ->paginatorToArray($collaborators);
        $maxOffset = count($collaborators);
        $this->log("collaborators found: ".count($collaborators));
        $maxOffset--;
        $forum = new Forum();
        $forum->setName($this->referenceBankName);
        $forum = $creator->create($forum, $root->getId(), 'claroline_forum', $user);
        $this->log("forum {$forum->getName()} created");

        for ($i = 0; $i < $this->nbSubjects; $i++) {
            $title = $this->container->get('claroline.utilities.lipsum_generator')->generateLipsum(5);
            $user = $collaborators[rand(0, $maxOffset)];
            $subject = new Subject();
            $subject->setTitle($title);
            $subject->setCreator($user);
            $this->log("subject $title created");
            $subject->setForum($forum);
            $manager->persist($subject);

            for ($j = 0; $j < $this->nbMessages; $j++) {

                $sender = $collaborators[rand(0, $maxOffset)];
                $message = new Message();
                $message->setSubject($subject);
                $message->setCreator($sender);
                $lipsum = $this->container->get('claroline.utilities.lipsum_generator')->generateLipsum(150, true);
                $message->setContent($lipsum);
                $manager->persist($message);

            }
            $manager->flush();
        }

        $manager->flush();

        $this->addReference("forum/{$this->referenceBankName}", $forum);
    }
}