<?php

namespace Claroline\CoreBundle\Library\Workspace;

use Claroline\CoreBundle\Library\Testing\FunctionalTestCase;

class CreatorTest extends FunctionalTestCase
{
    /** @var Creator */
    private $creator;

    protected function setUp()
    {
        parent::setUp();
        $this->loadUserFixture();
        $this->creator = $this->client->getContainer()->get('claroline.workspace.creator');
    }

    /**
     * @dataProvider invalidConfigProvider
     */
    public function testWorkspaceConfigurationIsCheckedBeforeCreation($invalidConfig)
    {
        $this->setExpectedException('RuntimeException');
        $user = $this->getFixtureReference('user/user');

        $this->creator->createWorkspace($invalidConfig, $user);
    }

    public function testWorkspaceCreatedWithMinimalConfigurationHasDefaultParameters()
    {
        $config = new Configuration();
        $config->setWorkspaceName('Workspace Foo');
        $config->setWorkspaceCode('WFOO');
        $user = $this->getFixtureReference('user/user');

        $workspace = $this->creator->createWorkspace($config, $user);

        $this->assertEquals(Configuration::TYPE_SIMPLE, get_class($workspace));
        $this->assertEquals('Workspace Foo', $workspace->getName());
        $roleRepo = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('ClarolineCoreBundle:Role');
        $this->assertEquals('visitor', $roleRepo->getVisitorRole($workspace)->getTranslationKey());
        $this->assertEquals('collaborator', $roleRepo->getCollaboratorRole($workspace)->getTranslationKey());
        $this->assertEquals('manager', $roleRepo->getManagerRole($workspace)->getTranslationKey());
    }

    public function testWorkspaceCanBeCreatedWithCustomParameters()
    {
        $user = $this->getFixtureReference('user/user');
        $config = new Configuration();
        $config->setWorkspaceType(Configuration::TYPE_AGGREGATOR);
        $config->setWorkspaceName('Workspace Bar');
        $config->setWorkspaceCode('WBAR');
        $config->setPublic(true);
        $config->setVisitorTranslationKey('Guest');
        $config->setCollaboratorTranslationKey('Student');
        $config->setManagerTranslationKey('Professor');

        $workspace = $this->creator->createWorkspace($config, $user);

        $this->assertEquals(Configuration::TYPE_AGGREGATOR, get_class($workspace));
        $this->assertEquals('Workspace Bar', $workspace->getName());
        $roleRepo = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('ClarolineCoreBundle:Role');
        $this->assertEquals('Guest', $roleRepo->getVisitorRole($workspace)->getTranslationKey());
        $this->assertEquals('Student', $roleRepo->getCollaboratorRole($workspace)->getTranslationKey());
        $this->assertEquals('Professor', $roleRepo->getManagerRole($workspace)->getTranslationKey());
    }

    public function invalidConfigProvider()
    {
        $firstConfig = new Configuration(); // workspace name is required
        $secondConfig = new Configuration();
        $secondConfig->setWorkspaceName('Workspace X');
        $secondConfig->setWorkspaceType('Some\Type'); // invalid workspace type

        return array(
            array($firstConfig),
            array($secondConfig)
        );
    }
}